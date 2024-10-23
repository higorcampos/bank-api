@extends('layouts.mail')

@section('content')
    <h1 class="email-header">
        Hello!
    </h1>
    <p class="email-paragraph">
        {{ __('auth.email_verification_message') }}
    </p>
    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <a href="{{ $token }}" class="button button-blue">
                                            {{ __('auth.email_verification') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p class="email-signature">
        Atenciosamente,<br>{{ config('app.name') }}
    </p>
@endsection
