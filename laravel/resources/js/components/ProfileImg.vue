<template>
  <label class="c-form--preview">
    <div class="drop">
      <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
      <input 
        class="c-form--input"
        id="file-sample"
        type="file"
        :name="name"
        @change="onFileChange"
      >
      <i class="fa fa-plus fa-3x c-form--plus" aria-hidden="true"></i>
      <img 
        class="c-form--radius"
        :class="classObject"
        id="file-preview"
        :src='uploadedImage'
      >
    </div>
  </label>
</template>

<script>
export default {
  props: {
    setImageData: {
      type: String,
      default: ""
    },
    name: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      uploadedImage: "",
    };
  },
  mounted() {
    if(this.setImageData) {
      return this.uploadedImage = this.setImageData;
    }else{
      return "";
    }
  },
  computed: {
    classObject() {
      return this.uploadedImage
      ? 'c-form--img c-form--img--radius'
      : 'c-form--img--none c-form--img--radius'
    },
  },
  methods: {
    onFileChange(e) {
      let files = e.target.files;

      if(files.length > 0) {
        this.createImage(files[0]);  // File情報格納
      }
    },
       // アップロードした画像を表示
          createImage(file) {
            let reader = new FileReader(); //File APIを生成
            reader.onload = (e) => {
              this.uploadedImage = e.target.result;
            };
      
            reader.readAsDataURL(file);
          },
  },
}
</script>

<style>

</style>