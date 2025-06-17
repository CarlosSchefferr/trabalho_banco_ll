📄 README: Sistema de Gestão de Não Conformidades (RNC)
Este projeto foi desenvolvido como parte dos requisitos para a disciplina de Banco de Dados II, focando na implementação de um sistema CRUD (Create, Read, Update, Delete) completo para a gestão de Registros de Não Conformidades (RNCs) utilizando PHP e PostgreSQL. O objetivo principal é demonstrar a interação eficiente entre uma aplicação web e um banco de dados relacional, abrangendo conceitos de modelagem, consultas complexas e manipulação de dados.

🚀 Funcionalidades
O sistema permite:

Registro de RNCs: Cadastrar novas não conformidades com informações detalhadas, como data de abertura, origem, produto/serviço afetado, cliente envolvido, responsável pela análise e descrição do problema.
Visualização Completa: Listar todas as RNCs registradas, apresentando dados de tabelas relacionadas (como Origem, Produto, Responsável, Cliente e Status) de forma clara e organizada.
Edição de RNCs: Atualizar informações de RNCs existentes, permitindo corrigir ou complementar dados conforme o andamento da análise e resolução.
Exclusão de RNCs: Remover registros de não conformidades do sistema.
Gerenciamento de Dados Relacionados: As RNCs se conectam a tabelas auxiliares (Origem, Responsável, Produto, Cliente, Status RNC) para garantir a integridade e padronização dos dados, utilizando chaves estrangeiras.
⚙️ Tecnologias Utilizadas
Backend:
PHP: Linguagem de programação para a lógica do servidor e manipulação dos dados.
PDO (PHP Data Objects): Extensão do PHP para conexão e interação segura com o banco de dados.
Banco de Dados:
PostgreSQL: Sistema de gerenciamento de banco de dados relacional (SGBD) robusto e de código aberto.
Frontend:
HTML5: Estrutura da página web.
CSS (Tailwind CSS e Bootstrap 5): Estilização para uma interface moderna e responsiva. O projeto integra o look-and-feel de "fintech" para uma estética limpa e profissional.
JavaScript: Para interações dinâmicas, como a confirmação de exclusão.
Feather Icons: Biblioteca de ícones leves e elegantes.
📁 Estrutura do Projeto
/CRUD TRABALHO/
├── index.php         # Arquivo principal com toda a lógica da aplicação (CRUD e interface)
└── (outros_arquivos) # Quaisquer outros arquivos ou diretórios que você tenha adicionado
🚀 Como Configurar e Rodar o Projeto
Para executar este sistema em sua máquina, siga os passos abaixo:

Pré-requisitos:

Servidor Web (ex: Apache ou Nginx)
PHP 7.4+ com extensão pdo_pgsql habilitada
Servidor PostgreSQL rodando
Configuração do Banco de Dados:

Acesse seu cliente PostgreSQL (ex: psql, pgAdmin, DBeaver).
Crie um novo banco de dados chamado sistema_qualidade.
Execute o script SQL fornecido (o mesmo que você compartilhou com as tabelas origem, departamento, responsavel, cliente, produto, status_rnc, rnc, acao_corretiva e os INSERTs de exemplo) para criar as tabelas e popular os dados iniciais.
Configuração do Projeto PHP:

Clone ou baixe este repositório para o diretório de documentos do seu servidor web (ex: htdocs para XAMPP/MAMPP, www para WAMP, /var/www/html para Apache no Linux). Certifique-se de que a pasta se chame CRUD TRABALHO ou ajuste o caminho.
Abra o arquivo index.php e verifique/ajuste as credenciais de conexão com o banco de dados na seção de configuração.
Importante: Em um ambiente de produção, remova ou comente as linhas ini_set('display_errors', 1); e ini_set('display_startup_errors', 1); para evitar a exposição de erros ao usuário.
Acesse a Aplicação:

Abra seu navegador e acesse a URL correspondente ao diretório do seu projeto. Se estiver usando XAMPP e a pasta for CRUD TRABALHO dentro de htdocs, a URL será: http://localhost/CRUD%20TRABALHO/
