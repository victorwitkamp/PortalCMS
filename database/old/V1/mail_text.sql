CREATE TABLE IF NOT EXISTS mail_text
(
    `name`             VARCHAR(32),
    `text`             TEXT,
    `ModificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `mail_text` (`name`, `text`)
VALUES ('ResetPassword',
        'Beste {USERNAME},<br><br>Open onderstaande link om je wachtwoord te resetten:<br><a href="{RESETLINK}">Reset wachtwoord</a><br><br>Met vriendelijke groet,<br><br>{SITENAME}');
INSERT INTO `mail_text` (`name`, `text`)
VALUES ('Signup',
        'Beste {USERNAME},<br><br>Klik op <a href="{ACTIVATELINK}">deze</a> link om uw account te activeren.<br>Indien de link niet werkt kunt u navigeren naar {ACTIVATEFORMLINK} en de volgende code invoeren: {CONFCODE}<br><br><br>Met vriendelijke groet,<br><br>{SITENAME}');
