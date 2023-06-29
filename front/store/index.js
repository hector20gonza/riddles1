export const state = () => ({
    apiResponse: null,
  });
  
  export const mutations = {
    setAPIResponse(state, response) {
      state.apiResponse = response;
    },
  };