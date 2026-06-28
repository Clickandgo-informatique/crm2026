FROM node:20

WORKDIR /app

# Installer les dépendances JS
COPY package.json ./
RUN npm install

# Copier le reste du projet
COPY . .

EXPOSE 5173

CMD ["npm", "run", "dev"]
