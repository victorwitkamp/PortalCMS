ALTER TABLE mail_templates ADD name varchar(32) default NULL after type;

INSERT INTO mail_templates (id, type, name, subject, body) VALUES (NULL,
            'system',
            'Signup',
            'Registratie',
            '<p>Beste {USERNAME},<br><br>Klik op <a href="{ACTIVATELINK}">deze</a> link om uw account te activeren.<br><br>Indien de link niet werkt kunt u navigeren naar {ACTIVATEFORMLINK} en de volgende code invoeren: {CONFCODE}<br><br>Met vriendelijke groet,<br><br>{SITENAME}</p>'
)

INSERT INTO mail_templates (id, type, name, subject, body) VALUES (NULL,
            'system',
            'ResetPassword',
            'Wachtwoord herstellen',
            '<p>Beste {USERNAME},<br><br>Open onderstaande link om je wachtwoord te resetten:<br><a href="{RESETLINK}">Reset wachtwoord</a><br><br>Indien de link niet werkt navigeer dan in uw browser naar deze URL:<br>{RESETLINK}<br><br>Met vriendelijke groet,<br><br>{SITENAME}</p>'
            )