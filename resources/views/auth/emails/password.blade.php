<!DOCTYPE html>
<html style="height:100%;font-family:Roboto, Helvetica, sans-serif;font-size:16px;color:#687390;" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password</title>
</head>
<body style="height:100%;font-family:Roboto, Helvetica, sans-serif;font-size:16px;color:#687390;" >
    <div style="height:100%;background-color:#282c37;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;padding-top:10%;padding-bottom:40px;" >
        <div style="max-width:600px;margin-top:auto;margin-bottom:auto;margin-right:auto;margin-left:auto;" >
            <a style="color:#2b90d9;text-decoration:none;display:block;margin-bottom:20px;" >
                <img alt="logo" src="https://dl.dropboxusercontent.com/u/49015136/pulse/logo-white.png" style="width:60px;min-height:60px;" >
                <span style="float:right;color:#cfd6e6;margin-top:10px;display:block;font-weight:300;font-size:2rem;" >Reset Password</span>
            </a>
            <div style="background-color:#fff;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-radius:4px;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;" >
                <h3 style="font-size:36px;margin-top:10px;font-weight:400;color:#333846;margin-bottom:30px;" >Don't Panic!</h3>
                <p style="margin-top:0;margin-bottom:15px;line-height:1.4;display:block;" >Forgot your password? No worries! Just click the link below to reset your password :)</p>

                <p style="margin-top:0;margin-bottom:15px;line-height:1.4;display:block;" >
                    <a href="{{route('password-reset', $token)}}" style="line-height:1.4;display:inline-block;margin-top:10px;margin-bottom:10px;margin-right:0;margin-left:0;background-color:#2b90d9;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;padding-top:10px;padding-bottom:10px;padding-right:30px;padding-left:30px;color:white;text-transform:uppercase;text-align:center;text-decoration:none;border-radius:3px;" >Reset Password</a>
                </p>

                <p style="margin-top:0;margin-bottom:15px;line-height:1.4;display:block;" >Your feedback is valuable - good or bad, both are welcome :) Reply to this email or write to <a href="mailto:pulse@kunalvarma.in" style="color:#2b90d9;text-decoration:none;" >pulse@kunalvarma.in</a>.
                </p>

                Cheers,<br>
                Kunal Varma.<br>
                Founder, Pulse.
            </div>
            <p style="margin-top:15px;margin-bottom:15px;line-height:1.4;display:block; text-align: center;">
                &copy; Pulse - 2016
            </p>
        </div>
    </div>
</body>
</html>