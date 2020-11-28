# saver-mail
Sistema de envios de e-mails

Para seu funcionamento em rede local, deve-se fazer as seguintes alterações:


- Executar arquivo BD_DDL.sql no banco de dados, nele contém as tabelas necessárias;
- Arquivo 'public_html\crm\src\usuario-cadastro.php' deve-se configurar um e-mail e senha de onde será origem dos e-mails;
- Arquivo lib\EnvioEmail.php deve-se configurar um e-mail e senha de onde será origem dos e-mails;

Mas caso queira ver funcionando sem rodar localmente, segue o link:
https://savermail.000webhostapp.com/crm/

Hospedado no servidor da 000webhost.
