<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box; 
        }
        .container{
            background: #edf2f7;
        }
        .content{
            background: transparent;
        }
        .content .logo{
            padding: 1rem;
        }
        .content .header{
            background: #ce4410;
            color: white;
        }
        .content .body{
            background: #ffffff;
        }
        .content .body .inner-body{
            background: transparent;
            padding: 1rem;
            border: 1px solid #ce4410;
            border-top: none;
        }
        .content .body .inner-body p{
            text-align: left;
            font-size: 1rem;
            color:#ce4410;
        }
        .content .body .inner-body .button{
            display: block;
            text-decoration: none;
            width: auto;
            height: auto;
            background: #ce4410;
            padding: 1rem;
            color: white;
            font-size: 1.25rem;
        }
        .content .footer{
            background: transparent;
            font-size: 0.5rem;
            color:white;
        }

        @media only screen and (max-width: 600px) {
            .content {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <table class="container" width="100%" cellspacing="0" cellpadding="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="570" cellspacing="0" cellpadding="0" role="presentation">
                    <tr>
                        <td class="logo" align="center">
                            <img src="https://res.cloudinary.com/dfzmi53wv/image/upload/v1690165616/FriendZone/App/Full_Logo_dy0a6o.png" width="50%"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="header" align="center">
                            <h1>{{$title}}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="body" align="center">
                           @yield('body')
                        </td>
                    </tr>
                    <tr>
                        <td class="footer" align="center">
                            <p>Powered by Yugi Nova</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
