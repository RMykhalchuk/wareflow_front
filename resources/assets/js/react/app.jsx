import React from 'react';
import ReactDOM from 'react-dom/client';
import ContainerList from './containers/ContainerList';

class ContainerApp {
    constructor() {
        this.containers = new Map();
    }

    mount(elementId, props = {}) {
        const element = document.getElementById(elementId);
        if (!element) {
            console.error(`Element with id "${elementId}" not found`);
            return;
        }

        if (this.containers.has(elementId)) {
            console.warn(`Container "${elementId}" is already mounted`);
            return;
        }

        const root = ReactDOM.createRoot(element);
        root.render(<ContainerList {...props} />);
        this.containers.set(elementId, root);
    }

    unmount(elementId) {
        const root = this.containers.get(elementId);
        if (root) {
            root.unmount();
            this.containers.delete(elementId);
        }
    }

    unmountAll() {
        this.containers.forEach((root, elementId) => {
            root.unmount();
        });
        this.containers.clear();
    }
}

window.ContainerApp = new ContainerApp();

export default ContainerApp;
