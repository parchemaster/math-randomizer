const lngs = {
    en: { nativeName: 'English' },
    sk: { nativeName: 'Slovenčina' }
};

const rerender = () => {
    $('body').localize();
    // $('title').text($.t('head.title'))
    // $('meta[name=description]').attr('content', $.t('head.description'))
}

$(function () {
    // https://www.i18next.com
    i18next
        // .use(i18nextHttpBackend)
        .use(i18nextBrowserLanguageDetector)
        .init({
            debug: true,
            fallbackLng: 'en',
            // backend: {
            //       loadPath: '../lang/{{lng}}.json'
            // }
            resources: {
                en: {
                    translation: {
                        "signin_heading": "Sign in",
                        "signup_heading": "Sign up",

                        "select_role": "Select your role",
                        "select_teacher_label": "Select your teacher",
                        "name_label": "Name",
                        "last_name_label": "Last name",
                        "email_label": "E-mail",
                        "password_label": "Password",

                        "login_button": "Log in",
                        "signup_button": "Sign up",

                        "no_account_text": "Don't have an account? Register here.",
                        "have_account_text": "Already have an account? Sign in.",

                        "email_error_empty": "Please, enter email.",
                        "email_error_invalid": "Incorrect email format.",
                        "email_error_duplicate": "A user with this email already exists.",
                        "password_error_empty": "Please, enter password.",
                        "password_error_short": "The password must have a minimum of 8 characters.",
                        "login_error": "Wrong email or password.",
                        "name_error_empty": "Please, enter your name.",
                        "name_error_format": "The name's field can only contain uppercase letters and lowercase letters",
                        "last_name_error_empty": "Please, enter your last name.",
                        "last_name_error_format": "The last name's field can only contain uppercase letters and lowercase letters",
                        "registration_success": "Registration was successful!",

                        "unknown_error": "Something went wrong."
                    }
                },
                sk: {
                    translation: {
                        "signin_heading": "Prihlásiť sa",
                        "signup_heading": "Registrovať sa",

                        "select_role": "Vyberte svoju rolu",
                        "select_teacher_label": "Vyberte svojho učiteľa",
                        "name_label": "Meno",
                        "last_name_label": "Priezvisko",
                        "email_label": "E-mail",
                        "password_label": "Heslo",

                        "login_button": "Prihlásiť sa",
                        "signup_button": "Registrovať sa",

                        "no_account_text": "Nemáte účet? Zaregistrujte sa tu.",
                        "have_account_text": "Už máte účet? Prihláste sa.",

                        "email_error_empty": "Prosím, zadajte e-mail.",
                        "email_error_invalid": "Nesprávny formát e-mailu.",
                        "email_error_duplicate": "Používateľ s týmto e-mailom už existuje.",
                        "password_error_empty": "Prosím, zadajte heslo.",
                        "password_error_short": "Heslo musí mať minimálne 8 znakov.",
                        "login_error": "Nesprávny e-mail alebo heslo.",
                        "name_error_empty": "Prosím, zadajte svoje meno.",
                        "name_error_format": "Pole s menom môže obsahovať len veľké a malé písmená.",
                        "last_name_error_empty": "Prosím, zadajte svoje priezvisko.",
                        "last_name_error_format": "Pole s priezviskom môže obsahovať len veľké a malé písmená.",
                        "registration_success": "Registrácia bola úspešná!",

                        "unknown_error": "Niečo sa pokazilo."
                    }
                }
            }
        }, (err, t) => {
            if (err) return console.error(err);
            jqueryI18next.init(i18next, $, { useOptionsAttr: true });

            // fill language switcher
            Object.keys(lngs).map((lng) => {
                const opt = new Option(lngs[lng].nativeName, lng);
                if (lng === i18next.resolvedLanguage) {
                    opt.setAttribute("selected", "selected");
                }
                $('#languageSwitcher').append(opt);
            });
            $('#languageSwitcher').change((a, b, c) => {
                const chosenLng = $(this).find("option:selected").attr('value');
                i18next.changeLanguage(chosenLng, () => {
                    rerender();
                });
            });

            rerender();
        });
});