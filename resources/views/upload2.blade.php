<?php
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>upload 2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset("css/upload2.css") }}">
</head>
<body>
    {{-- <div class="bg-mig"></div> --}}
    <div class="wrapper">
        <header>File Uploader</header>
        <form id="upload" method="POST" action="/users/upload" enctype="multipart/form-data">
            @csrf
            <input type="file" name="myFiles" class="filepond file-input" multiple hidden>
            <i class="fa-solid fa-images"></i>
            <span>Choose a file or drag it here</span>
        </form>
        <section class="progress-area">
            {{-- <li class="row">
                <i class="fas fa-file-alt file"></i>
                <div class="content">
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
            </li> --}}
        </section>
        <section class="uploaded-area">
            {{-- <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt file"></i>
                    <div class="details">
                        <span class="name">image_01.png</span>
                        <span class="size">70KB</span>
                    </div>
                </div>
                <i class="fas fa-check"></i>
            </li> --}}
        </section>
    </div>
    <script src="{{ asset('js/upload2.js') }}"></script>
</body>
</html>