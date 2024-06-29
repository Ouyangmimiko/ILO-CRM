import { setupWorker } from 'msw/browser';
import { handlers } from './handlers';

// 使用 TypeScript 类型声明
export const worker = setupWorker(...handlers);
