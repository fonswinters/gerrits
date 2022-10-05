<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<style>
    /* Base */

    body, body *:not(html):not(style):not(br):not(tr):not(code) {
        font-family: Helvetica, Arial, sans-serif;
        box-sizing: border-box;
    }

    body {
        background-color: #f5f8fa;
        color: #74787E;
        height: 100%;
        hyphens: auto;
        line-height: 1.4;
        margin: 0;
        -moz-hyphens: auto;
        -ms-word-break: break-all;
        width: 100% !important;
        -webkit-hyphens: auto;
        -webkit-text-size-adjust: none;
        word-break: break-all;
        word-break: break-word;

        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        font-feature-settings: "lnum";
    }

    p,
    ul,
    ol,
    blockquote {
        line-height: 1.4;
        text-align: left;
    }

    a {
        color: #3869D4;
    }

    a img {
        border: none;
    }

    /* Typography */

    h1 {
        color: #2F3133;
        font-size: 19px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    h2 {
        color: #2F3133;
        font-size: 16px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    h3 {
        color: #2F3133;
        font-size: 14px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    p {
        color: #74787E;
        font-size: 16px;
        line-height: 1.5em;
        margin-top: 0;
        text-align: left;
    }


    img {
        max-width: 100%;
    }

    /* Elements */

    .e-mail__wrapper {
        background-color: #f5f8fa;
        margin: 0;
        padding: 60px;
        width: 100%;
        -premailer-cellpadding: 60px;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .e-mail__content {
        margin: 0;
        padding: 0;
        width: 600px;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 600px;
    }

    .e-body {
        background-color: #FFFFFF;
        margin: 0;
        padding: 60px;
        width: 100%;
        -premailer-cellpadding: 60px;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .e-body-inner {
        background-color: #FFFFFF;
        margin: 0 auto;
        padding: 0;
        width: 570px;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 570px;
    }


    /* Components */

    .c-header-bar {
        padding: 20px 0;
        background-color: #213048;

        text-align: center;
        font-size: 20px;
        font-weight: bold;
        line-height: 32px;
        color: white;
    }

    .c-footer {
        margin: 0 auto;
        padding: 20px;
        background-color: #213048;
        text-align: center;
        font-size: 14px;
        line-height: 18px;
        color: white;

    }

    .c-button {
        border-radius: 3px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
        display: inline-block;
        -webkit-text-size-adjust: none;

        background-color: #213048;
        border-top: 10px solid #213048;
        border-right: 18px solid #213048;
        border-bottom: 10px solid #213048;
        border-left: 18px solid #213048;
    }

    .c-button__link {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    .c-button--yellow {
        background-color: #FFDA0A;
        color: #212933;
        border-color: #FFDA0A;
    }


    .c-button--green {
        background-color: #2ab27b;
        border-color: #2ab27b;
    }

    .c-button--red {
        background-color: #bf5329;
        border-color: #bf5329;
    }

    .table-label {
        padding-right: 24px;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .e-body-inner {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .c-button {
            width: 100% !important;
        }
    }
</style>

<table class="e-mail__wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="e-mail__content" width="100%" cellpadding="0" cellspacing="0">
                @yield('layout')
            </table>
        </td>
    </tr>
</table>
</body>
</html>
