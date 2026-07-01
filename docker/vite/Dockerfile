FROM node:22

WORKDIR /app

# Copier uniquement package.json pour installer les deps
COPY package.json ./

# Installer les dépendances dans Docker
RUN npm install

# Copier le reste du projet
COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev"]
