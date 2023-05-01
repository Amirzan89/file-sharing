const form = document.querySelector('form');
const progressArea = document.querySelector(".progress-area");
const fileInput = document.querySelector('input[type="file"]');
// const uploadingHTML = `<li class="row">
//                 <i class="fas fa-file-alt file"></i>
//                 <div class="content">
//                     <div class="details">
//                         <span class="name">terserah</span>
//                         <span class="size">4MB</span>
//                         <span class="percent">100%</span>
//                     </div>
//                     <div class="progress-bar">
//                         <div class="progress" style="width:${percent}%;"></div>
//                     </div>
//                     <div class="controls">
//                         <i class="fa-solid fa-play"></i>
//                         <i class="fa-solid fa-pause"></i>
//                         <i class="fa-solid fa-x"></i>
//                     </div>
//                 </div>
//             </li>`;
// const uploadedHTML = `<li class="row">
//                 <div class="content">
//                     <i class="fas fa-file-alt file"></i>
//                     <div class="details">
//                         <span class="name">image_01.png</span>
//                         <span class="size">70KB</span>
//                     </div>
//                 </div>
//                 <i class="fas fa-check"></i>
//             </li>`;
const rows = document.querySelectorAll(".row .content");
const idForm = [];
const format = {
    image: ["image/jpeg", "image/png"],
    pdf: "application/pdf",
    text: ["text/markdown", "text/plain", "application/msword"],
    programming: ["application/x-php", "application/javascript"],
};
const dataUpload = {
    url: "/users/upload",
    maxFileSize: "10MB",
};
//finding id form upload
rows.forEach((row) => {
    idForm.push(row.id);
});
form.addEventListener("click", () => {
    fileInput.click();
});
function validateUpload(Form) {
    // Send file to server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/users/validate/upload");
    xhr.onload = () => {
        response = xhr.responseText;
        console.log(response.body);
        if (xhr.status === 200) {
            return response.body;
        } else {
            throw response.body;
        }
    };
    const formData = new FormData(Form);
    xhr.send(formData);
}

function uploadData(Form, file,id) {
    // Send file to server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/users/upload");
    let paused = false;
    let cancelled = false;
    let startTime = 0;
    let endTime = 0;

    xhr.upload.onprogress = (event) => {
        if (paused) {
            return;
        }
        const percent = Math.floor((event.loaded / event.total) * 100);
        console.log("percent " + percent + "%");
        let progressHtml = ` <li class="row" id=${id}>
                <i class="fas fa-file-alt file"></i>
                <div class="content">
                    <div class="details">
                        <span class="name">${file.name}</span>
                        <span class="size">${file.size}</span>
                        <span class="percent">${percent}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress" style="width:${percent}%;"></div>
                    </div>
                    <div class="controls">
                        <i class="fa-solid fa-play"></i>
                        <i class="fa-solid fa-pause"></i>
                        <i class="fa-solid fa-x"></i>
                    </div>
                </div>
            </li>`;
        progressArea.insertAdjacentHTML("beforeend", progressHtml);
        const progressElem = progressArea.querySelector(`#${id}`);
        const pauseBtn = progressElem.querySelector(".pause-btn");
        const playBtn = progressElem.querySelector(".play-btn");
        const cancelBtn = progressElem.querySelector(".cancel-btn");

        pauseBtn.addEventListener("click", () => {
            paused = true;
            startTime = 0;
            endTime = 0;
            xhr.abort();
        });

        playBtn.addEventListener("click", () => {
            paused = false;
            startTime = new Date().getTime() - (endTime - startTime || 0);
            sendRequest();
        });

        cancelBtn.addEventListener("click", () => {
            cancelled = true;
            xhr.abort();
            progressElem.remove();
        });
    };

    const sendRequest = () => {
        if (cancelled) {
            return;
        }
        xhr.onload = () => {
            const response = xhr.responseText;
            console.log(response.body);

            if (xhr.status === 200) {
                console.log();
                alert("File uploaded successfully");

            } else {
                throw "File upload failed";
            }
        };

        const formData = new FormData(Form);
        xhr.send(formData);
    };
    sendRequest();
    return {
        xhr,
        pause: () => {
            paused = true;
            startTime = 0;
            endTime = 0;
            xhr.abort();
        },
        play: () => {
            paused = false;
            startTime = new Date().getTime() - (endTime - startTime || 0);
            sendRequest();
        },
        cancel: () => {
            cancelled = true;
            xhr.abort();
        },
    };
}
fileInput.addEventListener('change',(event)=>{
    event.preventDefault();
    console.log(fileInput.files);
    const resData = validateUpload(form);
    console.log('id files'+resData.data.data.id);
    // let uploads = [];
    // fileInput.files.forEach((file) => {
    //     const upload = uploadData(form, file, resData.data.data.id);
    //     uploads.push(upload);
    // });
        // console.log(uploads);
})
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


// var dropFileDiv = document.getElementById("drop-file-div");
// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    form.addEventListener(eventName, preventDefaults, false)
})
// Highlight drop area when file is dragged over it
form.addEventListener('dragover', highlight, false)
form.addEventListener('dragenter', highlight, false)
// Remove highlight when file is not dragged over drop area
form.addEventListener('dragleave', unhighlight, false)
form.addEventListener('dragend', unhighlight, false)
// Handle file drop
form.addEventListener('drop', handleDrop, false)
function preventDefaults (event) {
    event.preventDefault();
    event.stopPropagation()
}
function highlight(event) {
    form.classList.add('highlight')
}
function unhighlight(event) {
    form.classList.remove('highlight')
}
function handleDrop(event) {
    var fileListt = event.dataTransfer.files
    // handle dropped files here
    console.log(fileListt)
}