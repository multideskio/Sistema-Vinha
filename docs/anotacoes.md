# Projeto para Associação de Igrejas Vinha
## Sobre o Sistema
### Introdução
Bem-vindo ao sistema de administração financeira da Associação de Igrejas Vinha. Este sistema foi projetado para facilitar a devolução de dízimos e ofertas pelos membros das igrejas associadas. Com uma interface intuitiva e um conjunto abrangente de funcionalidades, o sistema permite aos usuários criar contas, realizar pagamentos e gerar relatórios detalhados por período. Além disso, ele também gerencia cobranças e envia lembretes via email e WhatsApp para garantir que todas as obrigações financeiras sejam cumpridas no prazo.
### Permissões
O sistema possui 4 tipos de permissões. A seguir estão as descrições e funções para a criação dessas permissões.
#### 1. Super Admin
- **Funções:**
  - Acesso a todas as configurações do sistema.
  - Cadastro e alteração de Regiões, Gerentes, Supervisores, Igrejas e Pastores.
  - Geração de cobranças.
  - Acesso a relatórios completos.
  - Cancelamento, bloqueio e gerenciamento de todos os usuários com a senha `adminsuper`.
#### 2. Gerente
- **Funções:**
  - Acesso aos seus Supervisores.
  - Visualização e geração de relatórios da sua gerência.
#### 3. Supervisor
- **Funções:**
  - Acesso à supervisão.
  - Visualização e geração de relatórios.
  - Cadastro de Pastores e Igrejas para sua supervisão.
#### 4. Pastor e Igreja
- **Funções:**
  - Geração do seu dízimo.
  - Visualização de seus próprios relatórios.
> **Nota:** Para implementar essas permissões, é necessário criar um usuário com a permissão específica em cada endpoint. Esta alteração foi relatada no dia 20 de maio e pode aumentar o desenvolvimento geral do sistema em uma semana.
### Formas de Pagamento
O sistema suporta as seguintes formas de pagamento:
- **PIX**
- **Boleto Bancário**
- **Cartão de Crédito** (Cielo e Bradesco)
### Configurações
Foi criada uma integração com o Google para a realização do login, porém essa conexão não é persistente. É necessário configurar com as credenciais da conta do Google da Vinha.
**Credenciais para login:**
```env
Google.Client.Id=
Google.Client.Secret=
```
Também é necessário criar as credenciais para o envio via SMTP do Google, garantindo a entrega da mensagem:
**Configurações SMTP:**
```env
SMTP.Host=smtp.gmail.com
SMTP.User=
SMTP.Pass=
SMTP.Port=587
```
> **Nota:** Essas configurações devem ser realizadas no arquivo `.env`.
### Funcionalidades Adicionais
- **Geração e gestão de cobranças:** O sistema pode automatizar a geração dos boletos e também permite cancelamento, bloqueio e gestão de usuários.
- **Envio de lembretes:** O sistema envia lembretes via email caso o boleto não tenha sido pago, e também manda mensagens de lembrete no WhatsApp do contribuinte.
- **Relatórios Detalhados:** Relatórios completos podem ser gerados por período, permitindo uma análise financeira detalhada.
### Detalhes Técnicos
#### Estrutura do Projeto
O sistema está sendo desenvolvido utilizando o framework CodeIgniter 4. A estrutura do projeto segue a arquitetura MVC (Model-View-Controller), o que ajuda na organização e manutenção do código.
#### Dependências
As principais dependências do projeto incluem:
- CodeIgniter 4
- Google API Client Library
- PHPMailer para envio de emails via SMTP
Para instalar todas as dependências, execute o comando:
```bash
composer install
```
#### Configurações Adicionais
Certifique-se de configurar corretamente o arquivo `app/Config/Database.php` para conectar ao seu banco de dados.
```php
public $default = [
    'DSN'      => '',
    'hostname' => 'localhost',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'database' => 'nome_do_banco',
    'DBDriver' => 'MySQLi',
    // Outros detalhes de configuração do banco de dados
];
```
> **Nota:** Não esqueça de criar o arquivo `.env` na raiz do projeto com as configurações específicas de seu ambiente.
### Testes e Deploy
#### Testes
O sistema utiliza PHPUnit para execução dos testes. Para rodar os testes, utilize o comando:
```bash
php vendor/bin/phpunit
```
#### Deploy
Para o deploy em produção, é recomendável seguir as boas práticas de segurança e configuração de servidores. Algumas dicas incluem:
- Desabilitar a exibição de erros.
- Configurar corretamente as permissões de pastas e arquivos.
- Realizar backups regulares do banco de dados.
---
Este documento deve ser atualizado conforme o desenvolvimento do projeto avança e novas funcionalidades são adicionadas.
```