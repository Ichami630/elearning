import React from 'react'

const Home = () => {
  const user = localStorage.getItem('user')
  const name = JSON.parse(user).name
  const title = JSON.parse(user).title 
  return (
    <div>welcome {title} {name}</div>
  )
}

export default Home