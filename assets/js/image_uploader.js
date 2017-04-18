/* 20170414 created by boggy */

class ImageUploader {

  /* init is a obj contains {ele, url, multiple, limit, maxSize} */
  constructor(init) {
    
    if (!init.ele || !init.url) {
      console.error('The ele and url param of ImageUploader constructor is required.');
    }

    ['addEventListener', 'dispatchEvent', 'removeEventListener'].forEach(f => this[f] = (...xs) => document[f](...xs));

    this.uploading = false;

    this.url = init.url;
    this.limit = init.limit;
    this.maxSize = init.maxSize;

    this.file = document.createElement('input');
    this.file.type = 'file';
    this.file.accept = 'image/jpeg';
    this.file.name = 'images[]';
    this.file.multiple = init.multiple;
    this.file.addEventListener('change', this.uploadFile.bind(this));

    this.form = document.createElement('form');
    this.form.enctype = 'multipart/form-data';
    this.form.hidden = true;
    this.form.append(this.file);

    init.ele.parentNode.insertBefore(this.form, init.ele);
    init.ele.addEventListener('click', this.openFileBrowser.bind(this));
  }

  openFileBrowser(e) {
    if (!this.uploading) this.file.dispatchEvent(new MouseEvent('click'));
    else alert('先前的檔案上傳中，請待上傳完畢，再上傳其他檔案。')
  }

  uploadFile(e) {
    let files = this.file.files;
    let limit = typeof this.limit === 'function' ? this.limit() : this.limit;
    
    if (!isNaN(limit) && files.length > limit) {
      alert(`您只能再上傳 ${limit} 張，請重新選取。`);
      return;
    }

    for (let i = 0; i < files.length; i++) {
      if (files[i].size / 1024 / 1024 > this.maxSize) {
        alert(`${files[i].name} 檔案大小超過限制 ${this.maxSize} MB，請重新調整後上傳`);
        return;
      }
    }

    // upload begin
    let uploader = this;
    uploader.uploading = false;

    fetch(this.url, {
      method: 'POST',
      body: new FormData(this.form)
    })
    .then(function(response) {
      uploader.uploading = false;

      if (response.status == 200) {
        response.text().then(function(text) {
          let event = new Event('success');
          event.data = text;
          uploader.dispatchEvent(event);
        });
      }
      else {
        let event = new Event('error');
        event.data = response.status;
        uploader.dispatchEvent(event);
      }
    })
    .catch(function(error) {
      uploader.uploading = false;
      let event = new Event('error');
      event.data = error;
      uploader.dispatchEvent(event);
    });
  }
}