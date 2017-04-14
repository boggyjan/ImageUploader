<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GraceGift Admin MultiImageUploader with jQuery UI Sortable</title>
  <link rel="stylesheet" href="assets/css/upload_list.css">
</head>
<body>
  
  <button class="upload-btn">上傳檔案</button>
  <div class="uploaded-list"></div>

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script src="assets/js/image_uploader.js"></script>
  <script>
    $(function() {

      'use strict';

      var list = document.querySelector('.uploaded-list');
      
      var uploader = new ImageUploader({
        ele: document.querySelector('.upload-btn'),
        url: 'upload.php', //sample php code in upload.php
        multiple: true,
        limit: () => { //limit 可以設計成可以直接帶入數字，或一個function（如這個範例這樣
          return 4 - list.querySelectorAll('.pic-item').length;
        },
        maxSize: 2 /* mb */
      });

      // 偵聽上傳成功事件
      uploader.addEventListener('success', function(event){
        let eles = JSON.parse(event.data);

        for (let i = 0; i < eles.length; i++) {
          let container = document.createElement('div');
          container.className = 'pic-item';

          let pic = document.createElement('img');
          pic.src = eles[i].path;
          
          let filename = document.createElement('div');
          filename.innerText = eles[i].filename;
          
          container.append(pic);
          container.append(filename);
          list.append(container);
        }
        saveListData(list);
      });

      // 偵聽上傳錯誤事件
      uploader.addEventListener('error', function(event) {
        alert('上傳檔案失敗，請檢查網路是否正常，或請技術人員排除錯誤。')
      });

      // 這邊的任務是 生成後端所需紀錄資料 > 傳到後端儲存 以下是範例
      function saveListData(list) {
        let items = list.querySelectorAll('.pic-item');
        let data = [];

        for (let i = 0; i < items.length; i++) {
          data.push({
            path: items[i].querySelector('img').src,
            filename: items[i].querySelector('div').innerText
          })
        }
        
        let json = JSON.stringify(data);
        console.log(json);

        /*
        傳回後端
        fetch(要存排序的後端網址, {
          method: 'POST',
          body: json
        })
        */
      }

      // 拖拉就照舊版一樣用jquery ui 的sortable      
      $('.uploaded-list').sortable({
        update: function(event, ui) {
          saveListData(this);
        }
      }).disableSelection();
    });

  </script>
</body>
</html>