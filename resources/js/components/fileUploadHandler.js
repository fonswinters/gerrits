/* ==========================================================================
   FileUpload handler
   - Handles the FileUpload component which has the proper classes.
   - Can only be used in conjunction with ChocolateFactory which handles the post.
 ========================================================================== */

const FileUploadHandler = {
    fileUploadWrapperClass: '.js-file-upload',
    fileUploadListClass: '.js-file-upload__list',
    fileUploadInputClass: '.js-file-upload__input',
    fileUploadButtonClass: '.js-file-upload__button',

    init: function () {

        const fileUploads = document.querySelectorAll(FileUploadHandler.fileUploadWrapperClass);
        const fileUploadsCount = fileUploads.length;

        if(isset(fileUploads) && fileUploadsCount !== 0){
            for(let i = 0; i < fileUploadsCount; i++){
                const fileUpload = fileUploads[i];
                this.initFileUploadHandler(fileUpload);
            }
        }
    },

    addFile: function (inputElement) {
        if(!inputElement) return;

        const node = inputElement.cloneNode();
        const list = inputElement.parentNode.parentNode.querySelector(FileUploadHandler.fileUploadListClass);
        let li = document.createElement('li');
        let remove = document.createElement('button');
        let textNode = document.createTextNode(inputElement.value.split("\\")[inputElement.value.split('\\').length - 1]);

        node.value = "";
        node.addEventListener('change', this.eventHandler);

        remove.setAttribute('type', 'button');
        remove.addEventListener('click', this.eventHandler);

        inputElement.parentNode.appendChild(node);
        inputElement.style.display = 'none';

        li.appendChild(inputElement);
        li.appendChild(textNode);
        li.appendChild(remove);

        list.appendChild(li);
    },

    removeFile: function (event) {
        event.target.parentNode.parentNode.removeChild(event.target.parentNode);
    },

    eventHandler: function (event) {
        switch (event.type) {
            case 'click':
                if (event.target.classList.contains(FileUploadHandler.fileUploadButtonClass.substring(1))) {
                    event.target.parentNode.querySelector(FileUploadHandler.fileUploadInputClass).click();
                }
                else {
                    FileUploadHandler.removeFile(event);
                }

                break;
            case 'change':
                FileUploadHandler.addFile(event.target);
                break;
        }
    },

    initFileUploadHandler: function (fileUpload) {
        const inputElement = fileUpload.querySelector(FileUploadHandler.fileUploadInputClass);
        const inputButtonElement = fileUpload.querySelector(FileUploadHandler.fileUploadButtonClass);
        inputButtonElement.addEventListener('click', this.eventHandler);
        inputElement.addEventListener('change', this.eventHandler);
    },


};

FileUploadHandler.init();