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

                        "unknown_error": "Something went wrong.",

                        //---------------------------------------------------//

                        "hello_label": "Hello, ",
                        "home_label": "Home",
                        "Create_Question": "Create Question",
                        "Create_Test": "Create Test",
                        "Assign_test": "Assign test to a student",
                        "How_to_use_Teacher": "How to use Teacher page",
                        "Logout_label": "Logout",
                        "Created_Tests": "Created Tests",
                        "id_label": "ID",
                        "opened_label": "Date Opened",
                        "closed_label": "Date Closed",
                        "total_points": "Total Points",
                        "Students_info": "Students info",
                        "ID_of_student": "ID of student",
                        "Generated_Tasks": "Generated Tasks",
                        "Submitted_Tasks": "Submitted Tasks",
                        "Points_label": "Points",
                        "ExportCSV_button": "Export to CSV",

                        "Create_a_new_question": "Create a new question",
                        "Name_of_question": "Name of question",
                        "Upload_Question": "Upload Question with LaTeX file:",
                        "Submit_button": "Submit",
                        "Create_a_new_test": "Create a new test",
                        "Start_date": "Start date",
                        "Deadline": "Deadline",
                        "Name_of_Test": "Name of Test",
                        "Total_Points": "Total Points",
                        "List_of_available_questions": "List of available questions to create a test",
                        "ID_of_question": "ID of question",
                        "Include_to_the_test": "Include to the test",
                        "Assign_a_test": "Assign a test by clicking on the name",
                        "Export_to_PDF": "Export to PDF",
                        "Available_features": "Available features and how to use them:",
                        "Create_a_question": "Create a question",
                        "feature1": "How: open page \"Create Question\", set name of the question, load LaTeX file, click on \"Submit\". Question will be created. Add image, if question works with image, to directory latex/zadanie99/images.",
                        "feature2": "How: open page \"Create Test\", set start date of test, set deadline for this test, set name of the test, set total points for this test, choose all questions you want to assign for this test, click on \"Submit\". Test will be created",
                        "feature3": "How: open page \"Assign test to a student\", click on name of the student you want to assign a test, on the next page select tests you want to assign, click on \"Submit\". Now student will see assigned tests for them.",
                        "feature4": "How: open teacher home page, then you can filter students by all available categories.",
                        "Create_a_test": "Create a test",
                        "Assign_test_to_a_student": "Assign test to a student",
                        "Filter_and_export_to_CSV": "Filter and export to CSV table with students.",
                        "To_export_table": "To export table in a CSV format: click on \"Export to CSV\" and you will download table in a CSV format.",

                        //---------------------------------------------------//

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

                        "unknown_error": "Niečo sa pokazilo.",

                        // --------------------------------------------------------

                        "hello_label": "Ahoj, ",
                        "home_label": "Domov",
                        "Create_Question": "Vytvoriť otázku",
                        "Create_Test": "Vytvoriť test",
                        "Assign_test": "Priradiť test študentovi",
                        "How_to_use_Teacher": "Ako používať stránku pre učiteľa",
                        "Logout_label": "Odhlásiť sa",
                        "Created_Tests": "Vytvorené testy",
                        "id_label": "ID",
                        "opened_label": "Dátum otvorenia",
                        "closed_label": "Dátum uzavretia",
                        "total_points": "Celkové body",
                        "Students_info": "Informácie o študentoch",
                        "ID_of_student": "ID študenta",
                        "Generated_Tasks": "Vygenerované úlohy",
                        "Submitted_Tasks": "Odoslané úlohy",
                        "Points_label": "Body",
                        "ExportCSV_button": "Exportovať do CSV",

                        "Create_a_new_question": "Vytvoriť novú otázku",
                        "Name_of_question": "Názov otázky",
                        "Upload_Question": "Nahrať otázku s LaTeX súborom:",
                        "Submit_button": "Odoslať",
                        "Create_a_new_test": "Vytvoriť nový test",
                        "Start_date": "Dátum začiatku",
                        "Deadline": "Termín",
                        "Name_of_Test": "Názov testu",
                        "Total_Points": "Celkové body",
                        "List_of_available_questions": "Zoznam dostupných otázok pre vytvorenie testu",
                        "ID_of_question": "ID otázky",
                        "Include_to_the_test": "Zahrnúť do testu",
                        "Assign_a_test": "Priradiť test kliknutím na meno",
                        "Export_to_PDF": "Exportovať do PDF",
                        "Available_features": "Dostupné funkcie a ako ich použiť:",
                        "Create_a_question": "Vytvoriť otázku",
                        "feature1": "Ako: otvorte stránku \"Vytvoriť otázku\", zadajte názov otázky, nahrajte LaTeX súbor, kliknite na \"Odoslať\". Otázka bude vytvorená. Pridajte obrázok, ak otázka pracuje s obrázkom, do adresára latex/zadanie99/images.",
                        "feature2": "Ako: otvorte stránku \"Vytvoriť test\", nastavte dátum začiatku testu, nastavte termín, zadajte názov testu, nastavte celkové body pre tento test, vyberte všetky otázky, ktoré chcete priradiť k tomuto testu, kliknite na \"Odoslať\". Test bude vytvorený",
                        "feature3": "Ako: otvorte stránku \"Priradiť test študentovi\", kliknite na meno študenta, ktorému chcete priradiť test, na ďalšej stránke vyberte testy, ktoré chcete priradiť, kliknite na \"Odoslať\". Študent uvidí priradené testy",
                        "feature4": "Ako: otvorte domovskú stránku učiteľa, potom môžete filtrovať študentov podľa všetkých dostupných kategórií",
                        "Create_a_test": "Vytvoriť test",
                        "Assign_test_to_a_student": "Priradiť test študentovi",
                        "Filter_and_export_to_CSV": "Filtrovať a exportovať tabuľku so študentmi do formátu CSV",
                        "To_export_table": "Pre export tabuľky vo formáte CSV: kliknite na \"Exportovať do CSV\" a stiahnete si tabuľku vo formáte CSV."

                        //---------------------------------------------------//

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