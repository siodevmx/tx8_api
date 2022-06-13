<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <style>
        td, th, div, p, a, h1, h2, h3, h4, h5, h6 {
            font-family: "Segoe UI", sans-serif;
            mso-line-height-rule: exactly;
        }
    </style>
    <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700,800,900&amp;amp;display=swap" rel="stylesheet">
    <style>
        @media (max-width: 600px) {
            .sm-h-60 {
                height: 60px !important;
            }

            .sm-w-full {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #fafafa;">
<div role="article" aria-roledescription="email" aria-label="" lang="en">
    <table style="background-color: #fafafa; font-family: 'Inter', -apple-system, 'Segoe UI', sans-serif; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr align="center" style="background-color: #fafafa;">
                        <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="padding-left: 24px; padding-right: 24px; padding-top: 32px; padding-bottom: 32px; text-align: center;">
                                    <a href="">
                                        <img src="https://i.ibb.co/0CvB7K8/Copia-de-Frente-Teporocho.png" class="sm-h-60" alt="" style="border: 0; line-height: 100%; max-width: 100%; vertical-align: middle; height: 56px; width: auto;">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </tr>
                    <tr>
                        <td style="width: 100%;">
                            <table align="center" class="sm-w-full" style="background-color: #ffffff; border-radius: 8px; margin-left: auto; margin-right: auto; width: 570px;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="padding: 45px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                        <div style="font-size: 16px;">
                                            <h1 style="font-weight: 700; font-size: 24px; margin-top: 0; text-align: left;">!Bienvenido, {{$newDeliverer->name}}!</h1>
                                            <p style="font-size: 16px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                                                Nos complace anunciarte que has sido invitado a colaborar con nosotros como repartidor. Usa el botón de abajo para configurar tu cuenta y comenzar:
                                            </p>
                                            <table align="center" style="margin-top: 30px; margin-bottom: 30px; margin-left: auto; margin-right: auto; text-align: center; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td align="center">
                                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                            <tr>
                                                                <td align="center" style="font-size: 16px;">
                                                                    <a href="https://tx8.com/login" target="_blank" style="display: inline-block; text-align: center; color: #ffffff; text-decoration: none; background-color: #d7bb7c; border-color: #d7bb7c; border-style: solid; border-width: 10px 18px; border-radius: 3px; font-weight: 500;">
                                                                        Configurar cuenta
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="font-size: 16px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                                                Esta es tu información de inicio de sesión:
                                            </p>
                                            <table style="margin-bottom: 21px; width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td style="background-color: #f4f4f5; border-radius: 2px; padding: 16px;">
                                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                            <tr>
                                                                <td style="font-family: Menlo, Consolas, monospace; font-size: 16px;">
                                                                    <strong>email:</strong> {{ $newDeliverer->email }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family: Menlo, Consolas, monospace; font-size: 16px;">
                                                                    <strong>clave temporal:</strong> {{ $newDeliverer->temp_pass }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="font-size: 16px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                                                Al acceder se te pedirá que ingreses una clave nueva, conservala ya que una vez que hayas creado una nueva no podrás ingresar con la clave temporal.
                                            </p>
                                            <p style="font-size: 16px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                                                Saludos,
                                                <br>
                                                El equipo de TX8
                                            </p>
                                            <table style="border-top-width: 1px; margin-top: 25px; padding-top: 25px; border-top-color: #eaeaec; border-top-style: solid;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 13px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">Si tienes problemas con el botón de arriba, copia y pega la URL a continuación en su navegador web.</p>
                                                        <p style="font-size: 13px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">{{ url('/login') }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table align="center" class="sm-w-full" style="margin-left: auto; margin-right: auto; text-align: center; width: 570px;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="font-size: 16px; padding: 45px;">
                                        <p style="font-size: 13px; line-height: 24px; margin-top: 6px; margin-bottom: 20px; text-align: center; color: #a8aaaf;">&copy; 2021 TX8. Todos los derechos reservados.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>

</html>
