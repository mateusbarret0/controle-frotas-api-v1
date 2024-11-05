Back Controle de Frotas - Laravel

Como Iniciar o Back End do Projeto - Controle de Frotas:

1 - Instalação do Composer:

    Verifique se o Composer está instalado em sua máquina pelo comando "composer -v" no terminal do vscode, se não tiver instalado, favor instalar a ultima versão do Composer (nosso projeto roda na 2.5.3).

2 - Navegue até a Pasta do Projeto:

    No terminal do vscode, digite "cd C:\Users\966788\Documents\GitHub\controle-frotas-api-v1" (caminho ficticio, favor verificar o caminho em que o repositório está salvo em sua máquina).

3 - Instale o Laravel:

    Instale o Laravel diretamente na pasta do projeto com o comando "composer create-project --prefer-dist laravel/laravel ./". Este comando instala o Laravel na pasta atual sem criar um novo diretório.

4 - Configure o Arquivo .env:

    Crie um arquivo dentro da pasta chamado ".env", logo após, faça uma copia do arquivo ".env.example" e cole no ".env" criado.
    Procure pela configuração da conexão com o banco de dados, algo semelhante a isso:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=

    substitua os seguintes campos: "DB_DATABASE=laravel" por "DB_DATABASE=alfaid" e "DB_PASSWORD=" por "DB_PASSWORD=acesso@123". Vai ficar algo semelhante a isso:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=alfaid
    DB_USERNAME=root
    DB_PASSWORD=acesso@123

5 - Crie o Banco de Dados Local:
