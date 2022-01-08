export const getCookie = name => {
  return (document.cookie.split(';')
      .map(c => c.trim().split('=').map(v => decodeURIComponent(v)))
      .find(c => c[0] === name) || [])[1];
};

export const getCookieJson = name => {
  try {
    return JSON.parse(getCookie(name));
  } catch (e) {
    return null;
  }
};
