import { http, HttpResponse} from 'msw';

interface Post {
  id: string;
  [key: string]: any; // This allows for additional properties
}

const allPosts = new Map<string, Post>();

export const handlers = [
  http.post('http://127.0.0.1:8000/api/register', async ({ request }: { request: Request }) => {

    //console.log('Handler is called'); // Add logging to check if handler is called

    const newPost = await request.json() as Post; // Type casting to Post

    allPosts.set(newPost.id, newPost);
    // check content of post
    /*const allPostsArray = Array.from(allPosts.values());
    console.log(allPostsArray);*/

    //console.log('Captured a "POST /sign-up" request');// Add submit check

    return HttpResponse.json(newPost, { status: 201 });
  }),

  http.post('http://127.0.0.1:8000/api/token/login', async ({ request }: { request: Request }) => {
    
    const info = await request.json() as Post;
    const username = info.username;
    const password = info.password;
    //console.log(`Username: ${username}, Password: ${password}`);

    allPosts.set(info.id, info);

    // mocking user authentificate
    if (username === 'user@test.com' && password === 'password') {
      return HttpResponse.json({ auth_token: 'mock-token' }, { status: 201 });
      //new Response(JSON.stringify({ auth_token: 'mock-token' }), { status: 200 });
    }

    return HttpResponse.json({ error: 'password error' }, { status: 401 });
    
    //new Response(JSON.stringify({ error: 'Invalid credentials' }), { status: 401 });
        
  }),

];
