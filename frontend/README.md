## Starting the application for development with Node.js

1. You will need the Stargaze Cinema API running.
2. Install dependencies with `pnpm i` or `npm i`.
3. Run `cp .env.example .env` in the terminal to create environment file.
4. Run `pnpm start:development` or `npm run start:development` in the terminal to start the application in development mode. Check `package.json` for other useful scripts.
5. Now you are ready to use the application:
    - Application is available at `localhost:3000`.
    - Administrative console is available at `console.localhost:3000`.

## Starting the application for development with Docker

1. You will need the Stargaze Cinema API running.
2. Run `cp .env.example .env` in the terminal to create environment file.
3. Run `docker build -f docker/Dockerfile -t stargaze-cinema --target=development .` to build the application from `Dockerfile`.
4. Run `docker run -p 3000:3000`.
5. Now you are ready to use the application:
    - Application is available at `localhost:3000`.
    - Administrative console is available at `console.localhost:3000`.

## Starting the application for production with Docker

1. You will need the Stargaze Cinema API running.
2. Run `cp .env.example .env` in the terminal to create environment file.
3. Run `docker build -f docker/Dockerfile -t stargaze-cinema .` to build the application from `Dockerfile`.
4. Run `docker run -p 80:80`.
5. Now you are ready to use the application:
    - Application is available at `localhost`.
    - Administrative console is available at `console.localhost`.
