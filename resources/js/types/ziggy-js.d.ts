declare module 'ziggy-js' {
  import type { Plugin } from 'vue';

  interface Route {
    uri: string;
    methods: ("GET" | "HEAD" | "POST" | "DELETE" | "PUT" | "PATCH" | "OPTIONS")[];
    domain?: string | null;
  }

  interface Config {
    url: string;
    port: number | null;
    defaults: Record<string, any>;
    routes: Record<string, Route>;
  }

  export const ZiggyVue: Plugin<[Config]>;
  export const Ziggy: Config;

  export function route(
    name?: string,
    params?: Record<string, any>,
    absolute?: boolean,
    config?: Config
  ): string;
}
