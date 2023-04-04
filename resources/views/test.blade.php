
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
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
            Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue",
            sans-serif;
            color: #000;
        }
        @page {
            margin: 0;
        }
        /* .dflex {
          display: flex;
          background: #f1f1f1;
          justify-content: space-around;
          padding: 10px;

          font-size: 20px;
          font-weight: bold;
        } */
        /* .dflex .col {
          padding: 10px;
          background: orange;
          width: 500px;
          text-align: center;
          margin: 10px auto;
          line-height: 2;
        } */
        body {
            margin-top: 20px;
            background: #eee;
        }

        .invoice {
            padding: 30px;
        }

        .invoice h2 {
            margin-top: 0px;
            line-height: 0.8em;
        }

        .invoice .small {
            font-weight: 300;
        }

        .invoice hr {
            margin-top: 10px;
            border-color: #ddd;
        }

        .invoice .table tr.line {
            border-bottom: 1px solid #ccc;
        }

        .invoice .table td {
            border: none;
        }

        .invoice .identity {
            margin-top: 10px;
            font-size: 1.1em;
            font-weight: 300;
        }

        .invoice .identity strong {
            font-weight: 600;
        }

        /* .grid {
          position: relative;
          width: 100%;
          background: #fff;
          color: #666666;
          border-radius: 2px;
          margin-bottom: 25px;
          box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
        } */
    </style>
</head>
<body>
<div class="container">

    <div class="table table-bordered row"  style="margin-left: 4px;">
        <div class="col">
            <br>
            <b class="text-center" style="padding: 20px;">Pay To:</b><br />
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>
            <b class="text-center" style="padding: 20px;"> Kalra Sweets </b><br>

        </div>
        <br><br><br><br> <br><br>
        <div class="col">
{{--            <img--}}
{{--                src="logo.png"--}}
{{--                alt="img"--}}
{{--                style="max-width: 45%; padding: 20px; display: block"--}}
{{--            />--}}
            <img
                src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/logo.png' ?>"
                alt="img"
                style="max-width: 45%; padding: 20px; display: block"
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
