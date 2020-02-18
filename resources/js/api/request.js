import axios from 'axios'

/**
 * Create an Axios Client with defaults
 */
const client = axios.create({
  baseURL: '/api',
})

/**
 * Request Wrapper with default success/error actions
 */
const request = function (options) {
  const onSuccess = function (response) {
    return response.data
  }

  const onError = function (error) {
    return Promise.reject(error.response)
  }

  return client(options)
    .then(onSuccess)
    .catch(onError)
}

export default request