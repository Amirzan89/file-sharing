const form = document.querySelector('form');
const progressArea = document.querySelector(".progress-area");
const fileInput = document.querySelector('input[type="file"]');
const dropArea = document.querySelector('form#uploadForm')
const dragHeader = dropArea.querySelector('span');
const rows = document.querySelectorAll(".row .content");
const idForm = [];
var id = 1, Files;
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
// inpFile.addEventListener('change',(event)=>{
//     file = event.target.files[0];
//     console.log('fileee')
//     console.log(file)
//     // showFile();
// })
dropArea.addEventListener('dragover',(event)=>{
    event.preventDefault();
    dropArea.classList.add('active');
    dragHeader.textContent = 'Release File Here';
});
dropArea.addEventListener('dragleave',(event)=>{
    event.preventDefault();
    dropArea.classList.remove('active');
    dragHeader.textContent = 'Choose a file or drag it here';
});

//finding id form upload
rows.forEach((row) => {
    idForm.push(row.id);
});
form.addEventListener("click", () => {
    fileInput.click();
});
function validateUpload(form) {
    const dataFile = [];
    const files = form.files;
    for(let i = 0; i < files.length; i++){
        dataFile.push(
            {'id':i+1},
            {'name':files[i].name},
            {'ext':files[i].name.split('.').pop()},
            {'size':files[i].size}
        );
    }
    // verify file to server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/users/validate/upload");
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    // xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.onload = () => {
        response = xhr.responseText;
        console.log(response.body);
        if (xhr.status === 200) {
            return response.body;
        } else {
            throw response.body;
        }
    };
    xhr.send(JSON.stringify(dataFile));
}

function uploadData(Form, file) {
    // Send file to server
    var size = file.size/1000;
    if(size < 1000){
        size += " KB";
    }else if(size > 1000){
        size = (size/1000)+" MB"
    }else if(size > 1000000){
        size = (size/1000000)+" GB"
    }
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/users/upload");
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    let paused = false;
    let cancelled = false;
    let startTime = 0;
    let endTime = 0;
    let progressHtml = `
    <li class="row" id="${id}">
        <div class="content">
            <i class="fas fa-file-alt file"></i>
            <div class="details">
                <span class="name">${file.name}</span>
                <span class="size">${size}</span>
                <span class="percent">0%</span>
            </div>
            <div class="progress-bar">
                <div class="progress" style="width:0%;"></div>
            </div>
            <div class="controls">
                <i class="fa-solid fa-pause pause-btn"></i>
                <i class="fa-solid fa-x cancel-btn"></i>
            </div>
        </div>
    </li>
    `;
    progressArea.insertAdjacentHTML("beforeend", progressHtml);
    console.log(progressArea.getElementById(`${id}`));
    const progressElem = progressArea.querySelector(`#${id}`);
    xhr.upload.onprogress = (event) => {
        // files.forEach((file)=>{
            if (paused) {
                return;
            }
            
            const percent = Math.floor((event.loaded / event.total) * 100);
            console.log("percent " + percent + "%");
            // var progressData = 
            // let progressHtml = `
            // <li class="row" id="${id}">
            //     <div class="content">
            //         <i class="fas fa-file-alt file"></i>
            //         <div class="details">
            //             <span class="name">${file.name}</span>
            //             <span class="size">${size}</span>
            //             <span class="percent">${percent}%</span>
            //         </div>
            //         <div class="progress-bar">
            //             <div class="progress" style="width:${percent}%;"></div>
            //         </div>
            //         <div class="controls">
            //             <i class="fa-solid fa-pause pause-btn"></i>
            //             <i class="fa-solid fa-x cancel-btn"></i>
            //         </div>
            //     </div>
            // </li>
            // `;
            // progressArea.insertAdjacentHTML("beforeend", progressHtml);
            
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
            // });
            id++;
        };
        
        const sendRequest = () => {
            if (cancelled) {
            return;
        }
        xhr.onload = () => {
            const response = xhr.responseText;
            // console.log(response.body);
            if (xhr.status === 200) {
                // console.log();
                console.log("File uploaded successfully");
            } else {
                throw "File upload failed";
            }
        };
        
        var formData = new FormData(Form);
        formData.append('email', 'Admin@gmail.com');
        formData.append('files', file);
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
dropArea.addEventListener('drop',(event)=>{
    event.preventDefault();
    files = event.dataTransfer.files;
    for(let i=0; i<files.length; i++){
        console.log(files[i]);
        console.log((files[i].size/1000)+" KB");
        console.log((files[i].size/100000)+" MB");
        console.log('iddd file '+id);
        uploadData(dropArea,files[i]);
        // ++id;
        console.log('id'+id)
    }
});
fileInput.addEventListener('change',(event)=>{
    var file = event.target.files[0];
    console.log(file)
    files = fileInput.files;
    console.log(files)
    for(let i=0; i<files.length; i++){
        console.log(files[i]);
        console.log('iddd file '+id);
        uploadData(dropArea,files[i]);
        id ++;
        console.log('id'+id)
    }
    // files.forEach((file)=>{
    //     console.log(file)
    //     // uploadData(dropArea,file);
    // })
})
// dropArea.onsubmit = (event) => {
//     event.preventDefault();
//     var formData = new FormData(dropArea);
//     formData.append('email', 'Admin@gmail.com');
//     formData.append('judul', inpJudul.value);
//     formData.append('isi_artikel', inpIsi.value);
//     formData.append('foto', file);

//     const xhr = new XMLHttpRequest();

//     const pauseBtn = document.createElement('button');
//     // pauseBtn.innerText = 'Pause';

//     const playBtn = document.createElement('button');
//     playBtn.innerText = 'Play';

//     const cancelBtn = document.createElement('button');
//     cancelBtn.innerText = 'Cancel';

//     pauseBtn.addEventListener('click', () => {
//         xhr.abort();
//     });

//     playBtn.addEventListener('click', () => {
//         sendRequest();
//     });

//     cancelBtn.addEventListener('click', () => {
//         xhr.abort();
//         formTambah.reset();
//         dropArea.querySelector('img').remove();
//         dropArea.innerHTML = `
//         <header>Drop File</header>
//         `;
//         dropArea.classList.remove('active');
//     });

//     const sendRequest = () => {
//         xhr.open('POST', '/page/edukasi');
//         xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

//         xhr.onload = () => {
//             const response = JSON.parse(xhr.responseText);
//             console.log(response);
//             formTambah.reset();
//             dropArea.querySelector('img').remove();
//             dropArea.innerHTML = `
//             <header>Drop File</header>`;
//             dropArea.classList.remove('active');
//             showPopup(response);
//         };

//         xhr.onerror = () => {
//             console.error('Request failed');
//             showPopup('Request failed');
//         };

//         xhr.send(formData);
//     };

//     sendRequest();

//     return false;
// };

// const resData = validateUpload(form);
// console.log('files '+resData.data);
// console.log('id files'+resData.data.data.id);
fileInput.addEventListener('change',(event)=>{
    try {
        event.preventDefault();
        const res = validateUpload(fileInput);
        // files = fileInput.files;
        // for(let i = 0; i < files.length; i++){
        //     console.log("data ke "+i);
        //     console.log(files[i]);
        //     console.log("size file "+files[i].size)
        //     console.log("nama file "+files[i].name)
        //     console.log("ext "+files[i].name.split('.').pop())
        //     console.log();
        // }
    } catch (error) {
        console.log("error "+error);
    }
})
// console.log(uploads);
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
// ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
//     form.addEventListener(eventName, preventDefaults, false)
// })
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