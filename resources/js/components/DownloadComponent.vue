<template>
  <div class="container">
      <loading :active.sync="isLoading" 
        :can-cancel="false"
        :is-full-page="false"></loading>
    <h1>File</h1>
    <div class="large-12 medium-12 small-12 cell">
      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>
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
            <td v-on:click="deleteFile(file.id,key)"> 
              <span class="icon-red">
                <span>
                  <i class="fas fa-trash-alt"></i>
                </span>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
import { EventBus } from '../app.js';
  export default {
    data(){
      return {
        files : [],
        isLoading: false,
        error : null
      }
    },
    components : {
        Loading
    },
    created(){
      EventBus.$on('uploadComplete', c => {
        this.list();
      });
    },
    mounted(){
      this.list();
    },
    methods: {
      list(){
        this.files = [];
        this.isLoading = true;
         axios.get('api/file/list').then((resp) => {
              this.files = resp.data;
              this.isLoading = false;
         });
      },
      downloadFiles(id,name){
        this.isLoading = true;
        this.error = null;
        let that =this;
        axios(
            {
                url: 'api/file/get/' + id,
                method: 'GET',
                responseType: 'blob', // important
            })
            .then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', name);
                document.body.appendChild(link);
                link.click();
                that.isLoading = false;
        }).catch((err) => {
            that.isLoading = false;
            console.log(err);
            this.error = err;
        });
      },
      deleteFile(id,key){
        this.isLoading = true;
        this.error = null;
        let that =this;
        axios(
            {
                url: 'api/file/delete/' + id,
                method: 'DELETE',
            })
            .then((response) => {
                this.list();
                that.isLoading = false;
        }).catch((err) => {
            that.isLoading = false;
            console.log(err);
            this.error = err;
        });
      }
    }
      
  }
</script>

<style lang="less" scoped>
.icon{
    font-size: 2rem;
    span {
      color: Mediumslateblue;
    }
  } 
.icon-red{
    font-size: 2rem;
    span {
      color: red;
    }
  } 
</style>







