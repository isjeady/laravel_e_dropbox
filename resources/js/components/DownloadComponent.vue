<template>
  <div class="container">
    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
    <h1>File</h1>
    <div class="large-12 medium-12 small-12 cell">
      <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Download</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(file, key) in files" class="file-listing" :key="file.name">
            <th scope="row">{{ file.id }}</th>
            <td>{{ file.name }}</td>
            <td v-on:click="downloadFiles(file.id,file.nameFile)">
              <span class="icon">
                <span>
                  <i class="fas fa-download"></i>
                </span>
              </span>
            </td>
            <td data-toggle="modal" data-target="#exampleModal" @click="idDelete = file.id">
              <span class="icon-red">
                <span>
                  <i class="fas fa-trash-alt"></i>
                </span>
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal -->
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirm Delete File ?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="idDelete = null">Close</button>
              <button
                type="button"
                class="btn btn-danger"
                data-dismiss="modal"
                v-on:click="deleteFile"
              >Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { EventBus } from "../app.js";
export default {
  data() {
    return {
      files: [],
      isLoading: false,
      error: null,
      idDelete: null
    };
  },
  components: {
    Loading
  },
  created() {
    EventBus.$on("uploadComplete", c => {
      this.list();
    });
  },
  mounted() {
    this.list();
  },
  methods: {
    list() {
      this.files = [];
      this.isLoading = true;
      axios.get("api/file/list").then(resp => {
        this.files = resp.data;
        this.isLoading = false;
      });
    },
    downloadFiles(id, name) {
      this.isLoading = true;
      this.error = null;
      let that = this;
      axios({
        url: "api/file/get/" + id,
        method: "GET",
        responseType: "blob" // important
      })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", name);
          document.body.appendChild(link);
          link.click();
          that.isLoading = false;
        })
        .catch(err => {
          that.isLoading = false;
          console.log(err);
          this.error = err;
        });
    },
    deleteFile() {
      if (this.idDelete){
        var id = this.idDelete;
        this.isLoading = true;
        this.error = null;
        let that = this;
        axios({
          url: "api/file/delete/" + id,
          method: "DELETE"
        })
          .then(response => {
            this.list();
            that.isLoading = false;
          })
          .catch(err => {
            that.isLoading = false;
            console.log(err);
            this.error = err;
          });
      }
    }
  }
};
</script>

<style lang="less" scoped>
.icon {
  font-size: 2rem;
  span {
    color: Mediumslateblue;
  }
}
.icon-red {
  font-size: 2rem;
  span {
    color: red;
  }
}
</style>







