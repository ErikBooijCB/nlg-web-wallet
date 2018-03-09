export const doGet = async url => {
  const response = await fetch(url, {
    cache:    'no-cache',
    method:   'GET',
    mode:     'cors',
    redirect: 'follow',
    referrer: 'no-referrer',
  });

  return {
    status: response.status,
    body:   await response.json(),
  };
};

export const doPost = async (url, data) => {
  const response = await fetch(url, {
    body:     JSON.stringify(data),
    cache:    'no-cache',
    headers:  {
      'content-type': 'application/json',
    },
    method:   'POST',
    mode:     'cors',
    redirect: 'follow',
    referrer: 'no-referrer',
  });

  return {
    status: response.status,
    body:   await response.json(),
  };
};
