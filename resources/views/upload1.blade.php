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
    <link rel="stylesheet" href="{{ asset("css/upload1.css") }}">
</head>
<body>
    <div class="bg-img">
        <img src="{{asset('images/background.jpg')}}" >
    </div>
    <div class="wrapper">
        <header>File Uploader</header>
        <form id="upload" method="POST" action="/users/upload" enctype="multipart/form-data">
            @csrf
            <i class="fa-solid fa-images"></i>
            <span>Choose a file or drag it here</span>
            <input type="file" name="myFiles" class="filepond file-input" multiple hidden >
        </form>
        <button id="submitBtn">Upload</button>
        <section class="progress-area">
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01.png+ Uploading</span>
                        <span class="size">4MB</span>
                        <span class="percent">50%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <div class="controls">
                        <i class="fa-solid fa-play"></i>
                        <i class="fa-solid fa-pause"></i>
                        <i class="fa-solid fa-x"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01.png+ Uploading</span>
                        <span class="size">4MB</span>
                        <span class="percent">50%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <div class="controls">
                        <i class="fa-solid fa-play"></i>
                        <i class="fa-solid fa-pause"></i>
                        <i class="fa-solid fa-x"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01.png+ Uploading</span>
                        <span class="size">4MB</span>
                        <span class="percent">50%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <div class="controls">
                        <i class="fa-solid fa-play"></i>
                        <i class="fa-solid fa-pause"></i>
                        <i class="fa-solid fa-x"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">terserah</span>
                        <span class="size">4MB</span>
                        <span class="percent">100%</span>
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
            </li>
        </section>
        <section class="uploaded-area">
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="size">70KB</span>
                    </div>
                    <div class="controls">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="size">70KB</span>
                    </div>
                    <div class="controls">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="size">70KB</span>
                    </div>
                    <div class="controls">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="size">70KB</span>
                    </div>
                    <div class="controls">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </li>
        </section>
    </div>
    <script src="{{ asset('js/upload1.js') }}"></script>
</body>
</html>