import axios from "axios"

export default () => ({
  signin({email, password}) {
    return axios.post("/user/access/signin/", {
      email,
      password
    });
  },
  signup({email, username, password}) {
    return axios.post("/user/access/signup/", {
      email,
      username,
      password
    })
  }
})
