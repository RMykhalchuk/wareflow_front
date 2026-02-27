import React from 'react';
import ReactDOM from 'react-dom/client';
import { IntlProvider } from 'react-intl';
import ContainerList from './containers/ContainerList';
import ContainerCreateForm from './containers/create/ContainerCreateForm';
import { localeMessages } from './containers/messages';
import type { ContainerAppProps, ContainerCreateAppProps } from './types/container';

type SupportedLocale = 'uk' | 'en';

function createRoot(
  elementId: string,
  locale: SupportedLocale,
  children: React.ReactElement,
  registry: Map<string, ReactDOM.Root>
): void {
  const element = document.getElementById(elementId);
  if (!element) {
    console.error(`Element with id "${elementId}" not found`);
    return;
  }
  if (registry.has(elementId)) {
    console.warn(`React root "${elementId}" is already mounted`);
    return;
  }
  const msgs = localeMessages[locale] || localeMessages.uk;
  const root = ReactDOM.createRoot(element);
  root.render(
    <IntlProvider locale={locale} messages={msgs} defaultLocale="uk">
      {children}
    </IntlProvider>
  );
  registry.set(elementId, root);
}

class ContainerApp {
  private roots: Map<string, ReactDOM.Root> = new Map();

  mount(elementId: string, props: ContainerAppProps): void {
    const locale: SupportedLocale = props.locale || 'uk';
    createRoot(elementId, locale, <ContainerList {...props} />, this.roots);
  }

  mountCreate(elementId: string, props: ContainerCreateAppProps): void {
    const locale: SupportedLocale = props.locale || 'uk';
    createRoot(elementId, locale, <ContainerCreateForm {...props} />, this.roots);
  }

  unmount(elementId: string): void {
    const root = this.roots.get(elementId);
    if (root) {
      root.unmount();
      this.roots.delete(elementId);
    }
  }

  unmountAll(): void {
    this.roots.forEach((root) => root.unmount());
    this.roots.clear();
  }
}

declare global {
  interface Window {
    ContainerApp: ContainerApp;
  }
}

window.ContainerApp = new ContainerApp();

export default ContainerApp;
