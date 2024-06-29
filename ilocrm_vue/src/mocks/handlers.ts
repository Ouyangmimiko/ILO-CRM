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

  http.get('/api/log-in', () => {
    return new HttpResponse(null, {
        status: 200,
        statusText: 'fake-token',//???
    })
  }),

  /*http.post('/api/login', (info) => {
    return res(
      ctx.status(200),
      ctx.json({
        token: 'fake-token',
      })
    );
  }),*/
];
