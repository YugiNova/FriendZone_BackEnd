@extends('mails.layout.master')

@section('body')
    <table class="inner-body" class="container" width="100%" cellspacing="0" cellpadding="0" role="presentation">
        <tr>
            <td align="center">
                <p>
                    Dear {{ $userName }},
                </p>
                <p>
                    Thank you for signing up for our service. We are excited to have you on board!
                </p>
                <p>
                    To complete your registration, please verify your email address by clicking on the link below:
                </p>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td>
                            <a href="{{ $url }}" class="button" target="_blank" rel="noopener">Verify email</a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td align="center">
                <p>
                    If you did not sign up for our service, please ignore this email.
                </p>
                <p>
                    Please note that if you do not verify your email address within 24 hours, your account will be deleted.
                </p>
                <p>
                    If you have any questions or concerns, please do not hesitate to contact us.
                </p>
                <p>
                    Best regards,
                </p>
            </td>
        </tr>
    </table>
@endsection
