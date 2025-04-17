FROM node:18.2-alpine3.15 as development

WORKDIR /app

COPY package*.json pnpm*.yaml tsconfig*.json ./

RUN corepack enable \
    && corepack prepare pnpm@7.1.5 --activate \
    && pnpm i --frozen-lockfile \
    && pnpm rb esbuild

COPY . .

EXPOSE 3000

CMD [ "pnpm", "start:development" ]

FROM development as build

ARG API_URL=http://localhost:8000/api
RUN echo "APP_API_URL=${API_URL}" > .env \
    && pnpm build

FROM nginx:1.21.6-alpine as production

COPY --from=build /app/dist /var/www/stargazecinema.com

COPY docker/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
