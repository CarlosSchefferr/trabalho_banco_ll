# 📄 Sistema de Gestão de Não Conformidades (RNC)

Este projeto foi desenvolvido como parte dos requisitos da disciplina de **Banco de Dados II**, com foco na implementação de um sistema **CRUD completo** para a gestão de **Registros de Não Conformidades (RNCs)**, utilizando **PHP** e **PostgreSQL**.

O objetivo principal é demonstrar a interação eficiente entre uma aplicação web e um banco de dados relacional, abrangendo conceitos de modelagem, consultas complexas e manipulação de dados.

---

## 🚀 Funcionalidades

O sistema permite:

- **Registro de RNCs**: Cadastro com data de abertura, origem, produto/serviço afetado, cliente, responsável e descrição do problema.
- **Visualização Completa**: Listagem de todas as RNCs com dados integrados de tabelas relacionadas.
- **Edição de RNCs**: Atualização de dados conforme o andamento das análises.
- **Exclusão de RNCs**: Remoção de registros indesejados.
- **Gerenciamento de Dados Relacionados**: Tabelas auxiliares (Origem, Produto, Responsável, Cliente, Status RNC) com integridade via chaves estrangeiras.

---

## ⚙️ Tecnologias Utilizadas

### Backend
- **PHP**: Lógica do servidor e manipulação de dados.
- **PDO (PHP Data Objects)**: Interface segura para conexão com o banco.

### Banco de Dados
- **PostgreSQL**: SGBD relacional robusto e open source.

### Frontend
- **HTML5**: Estrutura da aplicação.
- **CSS**: Tailwind CSS + Bootstrap 5 para estilo moderno e responsivo.
- **JavaScript**: Interações dinâmicas (ex: confirmação de exclusão).
- **Feather Icons**: Ícones leves e elegantes.
