<div style="background-color: #f0f0f0">
    <table
        cellpadding="0"
        cellspacing="24"
        role="presentation"
        style="
            max-width: 680px;
            width: 100%;
            margin: 0 auto;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
        "
    >
        <tr>
            <td>
                <img
                    src="{{ asset('assets/images/emails/banner.png') }}"
                    alt="{{ __('localization.emails_verification_code_banner_alt') }}"
                    style="width: 100%"
                />
            </td>
        </tr>
        <tr>
            <td
                style="
                    padding: 8px 32px;
                    background-color: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0px 4px 18px rgba(75, 70, 92, 0.1);
                "
            >
                <h2 style="color: #4d4d4d">
                    {{ __('localization.emails_verification_code_title') }}
                </h2>
                <div
                    style="
                        border: 1px dashed #9794a1;
                        border-radius: 6px;
                        padding-right: 20px;
                        padding-left: 20px;
                    "
                >
                    <p style="color: #4d4d4d">
                        {{ __('localization.emails_verification_code_label') }}
                    </p>
                    <h1 style="color: #4d4d4d">{{ $code }}</h1>
                    <p style="color: #4d4d4d">
                        {{ __('localization.emails_verification_code_expiration') }}
                    </p>
                </div>
                <div
                    style="
                        background-color: rgba(111, 107, 125, 0.12);
                        border-radius: 6px;
                        padding: 10px 15px 0 15px;
                        margin: 20px 0;
                    "
                >
                    <table>
                        <tr>
                            <td style="vertical-align: top">
                                <img
                                    src="{{ asset('assets/images/emails/info-circle.png') }}"
                                    alt="info-circle"
                                />
                            </td>
                            <td style="vertical-align: top">
                                <p style="font-size: 14px; color: #6f6b7d; margin-top: 3px">
                                    {{ __('localization.emails_verification_code_info') }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td
                style="
                    padding: 8px 32px;
                    background-color: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0px 4px 18px rgba(75, 70, 92, 0.1);
                "
            >
                <table>
                    <tr>
                        <td>
                            <h2 style="color: #4d4d4d">
                                {{ __('localization.emails_verification_code_help_title') }}
                            </h2>
                            <p style="color: #292928; font-size: 14px">
                                {{ __('localization.emails_verification_code_help_description') }}
                                <a
                                    href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2lExgepK6CZ4xOKbj7lYSYVQNjrUsSMP7v1o0ldear1358FlN-DZjgHHrtC1QPFe1pcJ_m9nVq"
                                    target="_blank"
                                    style="color: #1155cc"
                                >
                                    {{ __('localization.emails_verification_code_schedule_meeting') }}
                                </a>
                                {{ __('localization.emails_verification_code_or_contact') }} .
                            </p>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 53%; padding-bottom: 10px">
                            <div style="padding-bottom: 7px">
                                <img
                                    src="{{ asset('assets/images/emails/phone.png') }}"
                                    alt="phone"
                                    style="vertical-align: bottom; padding-right: 5px"
                                />
                                <span style="font-size: 15px">
                                    <a
                                        href="tel:{{ __('localization.emails.verification_code.phone') }}"
                                        style="text-decoration: none; color: #525252"
                                    >
                                        {{ __('localization.emails_verification_code_phone') }}
                                    </a>
                                </span>
                            </div>
                            <div>
                                <img
                                    src="{{ asset('assets/images/emails/mail.png') }}"
                                    alt="mail"
                                    style="vertical-align: bottom; padding-right: 5px"
                                />
                                <span style="font-size: 15px">
                                    <a
                                        href="mailto:{{ __('localization.emails.verification_code.email') }}"
                                        style="text-decoration: none; color: #525252"
                                    >
                                        {{ __('localization.emails_verification_code_email') }}
                                    </a>
                                </span>
                            </div>
                        </td>
                        <td style="width: 47%; padding-bottom: 8px">
                            <div style="padding-top: 10px; float: right">
                                <a
                                    href="https://www.instagram.com/wareflow.ai?igsh=ZGVpY2hucTQxbjVj"
                                    style="color: #fff"
                                    target="_blank"
                                >
                                    <img
                                        src="{{ asset('assets/images/emails/instagram.png') }}"
                                        alt="instagram"
                                        style="padding-right: 8px"
                                    />
                                </a>
                                <a
                                    href="https://www.facebook.com/profile.php?id=61573549611717"
                                    target="_blank"
                                    style="color: #fff"
                                >
                                    <img
                                        src="{{ asset('assets/images/emails/facebook.png') }}"
                                        alt="facebook"
                                        style="padding-right: 8px"
                                    />
                                </a>
                                <a
                                    href="https://www.linkedin.com/company/wareflow-ai/"
                                    target="_blank"
                                    style="color: #fff"
                                >
                                    <img
                                        src="{{ asset('assets/images/emails/linkedin.png') }}"
                                        alt="linkedin"
                                    />
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <div>
                        <p
                            style="
                                text-align: center;
                                color: rgba(168, 170, 174, 1);
                                font-size: 10px;
                            "
                        >
                            © {{ date('Y') }} {{ config('app.name') }}.
                            {{ __('localization.emails_verification_code_copyright') }}
                        </p>
                    </div>
                    <div>
                        <p
                            style="
                                text-align: center;
                                color: rgba(168, 170, 174, 1);
                                font-size: 10px;
                                line-height: 12px;
                                padding-left: 2em;
                                padding-right: 2em;
                            "
                        >
                            {{ config('app.name') }}
                            {{ __('localization.emails_verification_code_about') }}
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
