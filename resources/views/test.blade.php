@php
    define('DOMPDF_ENABLE_REMOTE' , false);
@endphp


<!DOCTYPE html>
<html>
<head>
    <title>Inioves</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous"
    />
    <style>
        body {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            font-family: DejaVu Sans, sans-serif !important;
        }
        body {
            margin-top: 20px;
        }
        .header-wrapper{
            width: 100%;
        }
        .header-wrapper .info-wrapper{
            width: 80%;
            display: inline-block;
        }
        .header-wrapper .image-wrapper{
            width: 19%;
            display: inline-block;
            /*position: relative;*/
        }
        .header-wrapper .image-wrapper img{
            width: 100%;
            /*position: absolute;*/
            /*top: -174px;*/
            /*bottom: 0;*/
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header-wrapper">
        <div class="info-wrapper">
            <br>
            <b class="text-center" style="padding: 20px;">Pay To:</b><br />
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>

        </div>
        <div class="image-wrapper">
{{--            <img--}}
{{--                src="/logo.png"--}}
{{--                alt="img"--}}
{{--            />--}}
            <img
                src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/logo.png' ?>"
                alt="img"
            />
        </div>
    </div>
    <div>
    </div>
    <table class="table table-bordered">
        <thead style="background-color: orange">
        <tr>
            <td><strong>#</strong></td>
            <td class="text-center"><strong>PROJECT</strong></td>
            <td class="text-center"><strong>HRS</strong></td>
            <td class="text-right"><strong>RATE</strong></td>
            <td class="text-right"><strong>SUBTOTAL</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>
                <strong>Template Design</strong>
            </td>
            <td class="text-center">15</td>
            <td class="text-center">$75</td>
            <td class="text-right">$1,125.00</td>
        </tr>
        <tr>
            <td>2</td>
            <td>
                <strong>Template Development</strong>
            </td>
            <td class="text-center">15</td>
            <td class="text-center">$75</td>
            <td class="text-right">$1,125.00</td>
        </tr>
        <tr class="line">
            <td>3</td>
            <td>
                <strong>Testing</strong>
            </td>
            <td class="text-center">2</td>
            <td class="text-center">$75</td>
            <td class="text-right">$150.00</td>
        </tr>
        <tr style="background-color: orange">
            <td colspan="3">Total</td>
            <!-- <td class="text-right"><strong></strong></td> -->
            <td colspan="2" class="text-center"><strong>$2,400.00</strong></td>
        </tr>
        </tbody>
    </table>
</div>
<footer>
    <p style="text-align: center; font-size: 20px; font-weight: bold">
        Copy Rights © 2020 — Dr-Code Ultimate Software Solutions
    </p>
</footer>
</body>
</html>
