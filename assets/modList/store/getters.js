
export const getServerMods = (state, getters) => {
    return state.mods.filter(mod => mod.type === 'server_side');
};
