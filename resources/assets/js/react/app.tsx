import React from 'react';
import ReactDOM from 'react-dom/client';
import { IntlProvider } from 'react-intl';
import ContainerList from './containers/ContainerList';
import { localeMessages } from './containers/messages';
import type { ContainerAppProps } from './types/container';

class ContainerApp {
  private containers: Map<string, ReactDOM.Root> = new Map();

  mount(elementId: string, props: ContainerAppProps): void {
    const element = document.getElementById(elementId);
    if (!element) {
      console.error(`Element with id "${elementId}" not found`);
      return;
    }

    if (this.containers.has(elementId)) {
      console.warn(`Container "${elementId}" is already mounted`);
      return;
    }

    const locale = props.locale || 'uk';
    const messages = localeMessages[locale] || localeMessages.uk;

    const root = ReactDOM.createRoot(element);
    root.render(
      <IntlProvider locale={locale} messages={messages} defaultLocale="uk">
        <ContainerList {...props} />
      </IntlProvider>
    );
    this.containers.set(elementId, root);
  }

  unmount(elementId: string): void {
    const root = this.containers.get(elementId);
    if (root) {
      root.unmount();
      this.containers.delete(elementId);
    }
  }

  unmountAll(): void {
    this.containers.forEach((root) => {
      root.unmount();
    });
    this.containers.clear();
  }
}

declare global {
  interface Window {
    ContainerApp: ContainerApp;
  }
}

window.ContainerApp = new ContainerApp();

export default ContainerApp;
