# Vue 3 + Vite

## Tecnologias Utilizadas
- Vue.js 3 (Composition API)
- Bootstrap 5 para estilização
- SweetAlert2 para alertas
- JSON Server para banco de dados falso (db.json)
- npm para gerenciamento de dependências

## Como rodar o projeto

### 1. Clone o repositório git bash
git clone <link-do-repositorio>
cd <nome-do-projeto>

# Instale as dependências:
npm install

# Inicie o JSON Server (simulando o banco de dados):
npx json-server --watch db.json --port 3000

# Inicie a aplicação Vue:
npm run serve

# Abra o navegador em: 
# Frontend http://localhost:5173/
# Backend  http://localhost:3000/