<?php
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>upload 1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    @vite('resources/css/app.css')
    <style>
        .progress-area::-webkit-scrollbar,
        .uploaded-area::-webkit-scrollbar{
            width: 0px;
        }
    </style>
</head>
<body class="max-h-screen select-none">
    <div> 
        <img src="{{asset('images/background.jpg')}}" class="object-cover w-full max-h-screen pointer-events-none">
    </div>
    <div class="absolute top-2/4 left-2/4 -translate-x-2/4 -translate-y-2/4 w-120 h-5/6 bg-white/20 rounded-2xl overflow-hidden" id="upload1">
    {{-- <div class="absolute top-2/4 left-2/4 -translate-x-2/4 -translate-y-2/4 w-120 h-5/6 bg-white/20 rounded-2xl overflow-hidden">
        <header class="text-black text-4xl text-center mt-7 font-medium">File Uploader</header>
        <form method="POST" action="/users/upload" enctype="multipart/form-data" class="w-5/6 h-1/4 mt-10 mx-auto flex flex-col items-center justify-center text-black border-blue-500 border-3 border-dashed rounded-2xl gap-4 cursor-pointer">
            <i class="fa-solid fa-images text-5xl"></i>
            <span class="text-2xl">Choose a file or drag it here</span>
            <input type="file" name="myFiles" class="filepond file-input" multiple hidden>
        </form>
        <div class="mt-12 h-2/4 w-5/6 mx-auto overflow-hidden flex flex-col text-black">
            <ul class="progress-area h-2/4 mb-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
                <li class="row relative bg-white rounded-xl">
                    <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
                    <div class="details absolute top-1 left-12">
                        <span class="name">image_01.png+ Uploading</span>
                        <div class="size">4MB</div>
                    </div>
                    <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
                        <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                            <div class="progress bg-blue-500 w-3/4 h-3"></div>
                        </div>
                        <span class="percent">50%</span>
                    </div>
                    <div class="controls absolute right-4 top-4 text-xl flex gap-2">
                        <i class="fa-solid fa-play cursor-pointer"></i>
                        <i class="fa-solid fa-pause cursor-pointer"></i>
                        <i class="fa-solid fa-x cursor-pointer"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl">
                    <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
                    <div class="details absolute top-1 left-12">
                        <span class="name">image_01.png+ Uploading</span>
                        <div class="size">4MB</div>
                    </div>
                    <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
                        <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                            <div class="progress bg-blue-500 w-3/4 h-3"></div>
                        </div>
                        <span class="percent">50%</span>
                    </div>
                    <div class="controls absolute right-4 top-4 text-xl flex gap-2">
                        <i class="fa-solid fa-play cursor-pointer"></i>
                        <i class="fa-solid fa-pause cursor-pointer"></i>
                        <i class="fa-solid fa-x cursor-pointer"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl">
                    <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
                    <div class="details absolute top-1 left-12">
                        <span class="name">image_01.png+ Uploading</span>
                        <div class="size">4MB</div>
                    </div>
                    <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
                        <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                            <div class="progress bg-blue-500 w-3/4 h-3"></div>
                        </div>
                        <span class="percent">50%</span>
                    </div>
                    <div class="controls absolute right-4 top-4 text-xl flex gap-2">
                        <i class="fa-solid fa-play cursor-pointer"></i>
                        <i class="fa-solid fa-pause cursor-pointer"></i>
                        <i class="fa-solid fa-x cursor-pointer"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl">
                    <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
                    <div class="details absolute top-1 left-12">
                        <span class="name">image_01.png+ Uploading</span>
                        <div class="size">4MB</div>
                    </div>
                    <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
                        <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                            <div class="progress bg-blue-500 w-3/4 h-3"></div>
                        </div>
                        <span class="percent">50%</span>
                    </div>
                    <div class="controls absolute right-4 top-4 text-xl flex gap-2">
                        <i class="fa-solid fa-play cursor-pointer"></i>
                        <i class="fa-solid fa-pause cursor-pointer"></i>
                        <i class="fa-solid fa-x cursor-pointer"></i>
                    </div>
                </li>
            </ul>
            <ul class="uploaded-area h-2/4 mt-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
                <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
                    <i class="fas fa-file-alt text-3xl absolute left-5"></i>
                    <div class="details absolute  left-15">
                        <span class="name">image_01 + Uploaded</span>
                        <div class="size">70KB</div>
                    </div>
                    <div class="controls absolute right-3 text-2xl flex gap-3">
                        <i class="fas fa-check"></i>
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </li>
            </ul>
        </div>
    </div> --}}
    @vite('resources/js/app.js')
</body>
</html>