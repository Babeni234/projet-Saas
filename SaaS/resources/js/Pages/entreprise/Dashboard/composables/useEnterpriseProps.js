import { inject, computed, provide } from 'vue';
import { usePage } from '@inertiajs/vue3';

const PROPS_KEY = Symbol('enterprise-inertia-props');

export function provideEnterpriseProps() {
    const page = usePage();
    const getProps = () => page.props;
    provide(PROPS_KEY, getProps);
    return page;
}

export function useEnterpriseProps() {
    const getProps = inject(PROPS_KEY, null);
    const page = usePage();

    return computed(() => (getProps ? getProps() : page.props));
}
