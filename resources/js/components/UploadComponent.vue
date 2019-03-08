<template>
  <div class="container">
      

    <div class="card" style="width: 25rem;">
        <div class="card-header">
            <div class="center">
                <span class="icon-big center">
                    <span>
                    <i class="fab fa-dropbox"></i>
                    </span>
                </span>
            </div>
        </div>
        <div class="card-body">
            <loading :active.sync="isLoading" 
            :can-cancel="false"
            :is-full-page="false"></loading>
            <h5 class="card-title">Add File to Dropbox</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <div v-if="error" class="alert alert-danger" role="alert">
                {{ error }}
            </div>
            <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload()"/>
            <div v-on:click="addFiles()">
                    <span class="icon">
                            <span>
                            <i class="fas fa-plus" :class="{ 'disabled' : block()}"></i>
                    </span>
                </span>
            </div>
            <div class="large-12 medium-12 small-12 cell">
                <div v-for="(file, key) in files" class="file-listing" :key="file.name">
                    {{ file.name }} <span class="remove-file" v-on:click="removeFile( key )"><i class="fas fa-trash-alt"></i></span>
                </div>
            </div>
            <div class="large-12 medium-12 small-12 cell center">
                <div v-if="files.length > 0" v-on:click="submitFiles()">
                    <span class="icon">
                            <span>
                            <i class="fas fa-cloud-upload-alt"></i>
                            </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
import { EventBus } from '../app.js';
  export default {
    data(){
      return {
        files: [],
        isLoading: false,
        maxN : 3,
        error : null
      }
    },
    components : {
        Loading
    },
    methods: {
        block(){
            return this.files.length >= this.maxN;
        },
      addFiles(){
        if(!this.block()){
            this.$refs.files.click();
        }
      },
      submitFiles(){
        this.isLoading = true;
        this.error = null;
        let formData = new FormData();

        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i];
          formData.append('files[' + i + ']', file);
        }
        //formData.append('first_name', '');
        //formData.append('last_name', '');

        let that =this;
        axios.post( 'api/file/store',
          formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
          }
        ).then(resp => {
            console.log('SUCCESS!!');
            that.files = [];
            EventBus.$emit('uploadComplete', true);
            that.isLoading = false;
            }
        ).catch(err => {
          console.log('FAILURE!!');
          that.isLoading = false;
          console.log(err);
          this.error = err;
        });

      },
      handleFilesUpload(){
        let uploadedFiles = this.$refs.files.files;
        for( var i = 0; i < uploadedFiles.length; i++ ){
            if(i < this.maxN){
                this.files.push( uploadedFiles[i] );
            }
        }
      },
      removeFile( key ){
        this.files.splice( key, 1 );
      }
    }
  }

</script>

<style lang="less" scoped>
  input[type="file"]{
    position: absolute;
    top: -500px;
  }

  .center{
      text-align : center;
  }

  div.file-listing{
    width: 100%;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #cfdce8;
    line-height: 30px;
  }

  span.remove-file{
    font-size: 1.5rem;
    color: red;
    cursor: pointer;
    float: right;
  }
  .disabled{
      color: #cfdce8;
  }
  .icon{
    font-size: 2rem;
    span {
      color: Mediumslateblue;
      cursor: pointer;
    }
  } 
  .icon-big{
    font-size: 4.8rem;
    span {
      color: Mediumslateblue;
      cursor: pointer;
    }
  } 
</style>







