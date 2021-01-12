import axios from 'axios';
import * as types from './mutationTypes';

export const fetchMods = ({ state, commit }) => {
    return axios(state.modsEndpoint)
        .then(({ data }) => commit(types.RECEIVE_MODS_SUCCESS, data))
        .catch((error) => {
            commit(types.RECEIVE_MODS_ERROR, error)
            alert('Wystąpił błąd podczas pobierania modów');
        });
};

export const initStore =  async ({ state, commit, dispatch }) => {
    await Promise.all([
        dispatch('fetchMods'),
    ]);

    commit(types.SET_LOADED);
}
