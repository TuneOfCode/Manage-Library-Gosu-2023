<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xác thực email của thành viên {{ $full_name }}</title>
</head>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        color: #333;
    }

    .wrapper {
        display: flex;
        flex-direction: column;
        /* align-items: center;
        justify-content: center; */
    }

    .memberName,
    .email {
        color: #e7650e;
        font-weight: bold;
    }

    .wrapper-content {
        /* display: block; */
        margin: 5px;
        color: #333;
        padding: 8px 10px;
    }

    .link {
        color: #fff;
        background-color: #e7650e;
        padding: 8px 8px;
        border: none;
        border-radius: 5px;
        width: 100px;
        text-align: center;
        margin: 20px auto;
        display: block;
        font-weight: bold;
        cursor: pointer !important;
    }

    .danger {
        color: #f00;
        font-weight: bold;
    }

    footer {
        margin-top: 20px;
    }

    .copyright {
        padding-top: 20px;
        color: #f00;
    }

    .wrapper-content>.link {
        cursor: pointer;
    }
</style>

<body>
    <div class="wrapper">
        <div class="wrapper-content">
            <h1>Chào mừng thành viên mới, <span class="memberName">{{ $full_name }}</span>!</h1>
            <p>
                Bạn vừa đăng ký tài khoản trên website của chúng tôi.
            </p>
            <p class="email">Email: {{ $email }}</p>
            <p>
                Đây là mã xác thực của bạn: <span class="memberName">{{ $otp_email_code }}</span>
            </p>

            {{-- <p id="noti-time">Thời gian hết hạn còn
                <span id="otp_email_expired_at" title="{{ $otp_email_expired_at }}" class="memberName">
                    59</span><span class="memberName">s</span>
            </p> --}}

            {{-- <form id="formResend" style="display: none" action="{{ $link }}" method="post">
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="full_name" value="{{ $full_name }}">
                <input type="hidden" name="otp_email_code" value="{{ $otp_email_code }}">
                <input type="hidden" name="otp_email_expired_at" value="{{ $otp_email_expired_at }}">
                <button style="cursor: pointer;" class="link" type="submit">Nhận lại mã</button>
            </form> --}}
            {{-- <script type="text/javascript">
                alert('Mã xác thực đã được gửi đến email của bạn!');
                console.log('Mã xác thực đã được gửi đến email của bạn!');
                const otp_email_expired_at = document.getElementById('otp_email_expired_at').getAttribute('title');
                const countDownDate = new Date(otp_email_expired_at).getTime();
                document.querySelector('#noti-time').style.display = 'block';
                document.querySelector('#formResend').style.display = 'none';
                const x = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = countDownDate - now;
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById("otp_email_expired_at").innerHTML = seconds;
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("otp_email_expired_at").innerHTML = "EXPIRED";
                        document.querySelector('#noti-time').style.display = 'none';
                        document.querySelector('#formResend').style.display = 'block';
                    }
                }, 1000);
            </script> --}}
            <strong>
                <i class="danger">Vui lòng không cung cấp mã này cho bất kỳ ai khác!</i>
            </strong>
            <footer>
                Cảm ơn rất nhiều,<br>
                <span class="copyright"><strong style="color:'red'">&copy; Thực tập sinh Gosu 2023</strong></span>
            </footer>
        </div>
    </div>
</body>

</html>
