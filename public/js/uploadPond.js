// const FilePond = require('filepond');

const form = document.getElementById('upload');
const submitBtn = document.getElementById('submitBtn');
const progressArea = document.querySelector(".progress-area");
const fileInput = document.querySelector('input[type="file"]');

const format = {
    image: ["image/jpeg", "image/png"],
    pdf: "application/pdf",
    text: ["text/markdown", "text/plain", "application/msword"],
    programming: ["application/x-php", "application/javascript"],
};
const dataUpload = {
    url:'/pond/upload',
    maxFileSize:'10MB'
} 
form.addEventListener("click", () => {
    fileInput.click();
});
function uploadData(formData){
    // Send file to server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", dataUpload);
    xhr.onload = ()=>{
        // response = JSON.parse(xhr.responseText);
        response = xhr.responseText;
        console.log(response.body);
        if (xhr.status === 200) {
            console.log();
            alert("File uploaded successfully");
        } else {
            throw "File upload failed";
        }
    };
    xhr.send(formData);
} 
submitBtn.addEventListener("click", (event)=>{
    event.preventDefault();
    const formData = new FormData(form);
    const pond = FilePond.create(form, {
        server: {
            url: dataUpload.url,
        },
    });
    // FilePond.setOptions({
    //     server: {
    //         url: dataUpload.url,
    //     },
    // });
    // const formData = new FormData();
    uploadData(pond);
    // form.submit();
})
function pond(element){
    const pond = FilePond.create(element, {
        allowMultiple: true,
        allowFileTypeValidation: true,
        acceptedFileTypes: ["image/png", "image/jpeg", "image/gif"],
        allowFileSizeValidation: true,
        maxFileSize: dataUpload.maxFileSize,
    });
    pond.setOptions({
        server: {
            url: dataUpload.url,
        },
    });
    return pond;
    // apiAdapter.post('/users/upload',formData);
}
// const myForm = document.getElementById('myForm');
// submitBtn.addEventListener('click', function(e) {
//     e.preventDefault(); // Prevent default form submission behavior
//     axios.post(myForm.action, new FormData(myForm))
//         .then(function(response) {
//             console.log(response.data); // Log response data
//         })
//         .catch(function(error) {
//             console.error(error); // Log error
//         });
// });



// fileInput.addEventListener("change", () => {
//     try{
//         const formData = new FormData(pond('form'));
//         const files = fileInput.files;
//         // for (let i = 0; i < files.length; i++) {
//             const file = files[i];
//             console.log(file);
//             // Do something with the file
//             console.log(file.name);
//             if (!format.pdf.includes(file.type)) {
//                 throw "File type is not allowed";
//             }
//             formData.append(`file_${i}`, file);
//         // }
//         // Send file to server
//         const xhr = new XMLHttpRequest();
//         xhr.open("POST", "/users/upload");
//         xhr.onload = ()=>{
//             // response = JSON.parse(xhr.responseText);
//             response = xhr.responseText;
//             console.log(response.body);
//             if (xhr.status === 200) {
//                 console.log();
//                 alert("File uploaded successfully");
//             } else {
//                 throw "File upload failed";
//             }
//         };
//         xhr.send(formData);
//         console.log('done upload');
//     }catch(error){
//         console.log(error);
//     }
// });


// Prevent default drag behaviors
["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    form.addEventListener(eventName, preventDefaults, false);
});
// Highlight drop area when file is dragged over it
form.addEventListener("dragover", highlight, false);
form.addEventListener("dragenter", highlight, false);
// Remove highlight when file is not dragged over drop area
form.addEventListener("dragleave", unhighlight, false);
form.addEventListener("dragend", unhighlight, false);
// Handle file drop
form.addEventListener("drop", handleDrop, false);
function preventDefaults(event) {
    event.preventDefault();
    event.stopPropagation();
}
function highlight(event) {
    form.classList.add("highlight");
}
function unhighlight(event) {
    form.classList.remove("highlight");
}
function handleDrop(event) {
    var fileList = event.dataTransfer.files;
    // handle dropped files here
    console.log(fileList);
}