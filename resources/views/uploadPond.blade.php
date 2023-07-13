<?php
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>gabut</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset("css/upload1.css") }}">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
</head>
<body>
    <div class="bg-img">
        <img src="{{asset('images/background.jpg')}}" alt="">
    </div>
    <div class="wrapper">
        <header>File Uploader Laravel</header>
        <form id="upload" method="POST" action="/pond/upload" enctype="multipart/form-data">
            @csrf
            <input type="file" name="files" class="filepond file-input" multiple >
        </form>
        <button id="submitBtn">Upload</button>
        <section class="progress-area">
            <li class="row">
                <i class="fas fa-file-alt"></i>
                <div class="content">
                    <div class="details">
                        <span class="name">image_01.png+ Uploading</span>
                        <span class="percent">50%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress"></div>
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
                        <span class="percent">70KB</span>
                    </div>
                </div>
                <i class="fas fa-check"></i>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="percent">70KB</span>
                    </div>
                </div>
                <i class="fas fa-check"></i>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="percent">70KB</span>
                    </div>
                </div>
                <i class="fas fa-check"></i>
            </li>
            <li class="row">
                <div class="content">
                    <i class="fas fa-file-alt"></i>
                    <div class="details">
                        <span class="name">image_01 + Uploaded</span>
                        <span class="percent">70KB</span>
                    </div>
                </div>
                <i class="fas fa-check"></i>
            </li>
        </section>
    </div>
    {{-- @section('scripts')
        const dataUpload = {
            url:'/users/upload',
            maxFileSize:'10MB'
        } 
        const pond = FilePond.create(upload, {
            allowMultiple: true,
            allowFileTypeValidation: true,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
            allowFileSizeValidation: true,
            maxFileSize: dataUpload.maxFileSize,
        });
        pond.setOptions({
            server:{
                url:dataUpload.url,
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                }
            }
        })
    @endsection --}}
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="{{ asset('js/uploadPond.js') }}"></script>
</body>
</html>