const searchResult = {
  tripInfos : {
      "ONWARD": []
  }
};

const paxInfo = {"ADULT": "1"};

const routeInfos = [];

const airportLists = []

const form_data = {}

const destinations = {
  data: [
    {title : 'Ladakh', url: '#', thumbSrc: './assets/traveltour/images/destination1.jpg'},
    {title : 'Rajasthan', url: '#', thumbSrc: './assets/traveltour/images/destination2.jpg'},
    {title : 'Goa', url: '#', thumbSrc: './assets/traveltour/images/destination3.jpg'},
    {title : 'Kerala', url: '#', thumbSrc: './assets/traveltour/images/destination4.jpg'},
    {title : 'Varanasi', url: '#', thumbSrc: './assets/traveltour/images/destination5.jpg'},
    {title : 'Himachal Pradesh', url: '#', thumbSrc: './assets/traveltour/images/destination6.jpg'}
  ],
  url: "#"
}

const clients = [
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'},
  {image : './assets/traveltour/images/client1.png'},
  {image : './assets/traveltour/images/client2.png'},
  {image : './assets/traveltour/images/client3.png'},
  {image : './assets/traveltour/images/client4.png'},
  {image : './assets/traveltour/images/client5.png'},
  {image : './assets/traveltour/images/client6.png'}
];

const dummyPackageData = [
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 1',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 2',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 3',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  },
  {
    url : '#',
    thumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Dummy 4',
    search_price: 1000,
    package_type: 'Flexi package',
    days: 4,
    nights: 3
  }
];

const dummyBlogs = [
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1', url:'/blog/culture'}, {id: 2, name: 'cat2', url:'/blog/culture'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  {
    url : '/blog/detail/testing-usewr',
    blogthumbSrc : '/assets/traveltour/images/noimagebig.jpg',
    image : '/assets/traveltour/images/noimagebig.jpg',
    title : 'Test Blog',
    author : 'Test Author',
    date : '22 Jun 2023',
    categories : [{id: 1, name: 'cat1'}, {id: 2, name: 'cat2'}],
    socialLinks : [{fb : 'https://www.facebook.com/', twitter: 'https://twitter.com/', whatsapp: 'https://web.whatsapp.com'}],
    description : 'Test Description'
  },
  
];

const dummyTestimonials = [
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
  {
    url: '/testimonial/test',
    testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
    imageSrc: '/assets/traveltour/images/noimagebig.jpg',
    name: 'test',
    description : 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
  },
];
const blogCategories = [
  {
    url : '/blog/culture',
    title : 'Category1'
  },
  {
    url : '/blog/culture',
    title : 'Category2'
  },
  {
    url : '/blog/culture',
    title : 'Category3'
  },
  {
    url : '/blog/culture',
    title : 'Category4'
  },
  {
    url : '/blog/culture',
    title : 'Category5'
  },
  {
    url : '/blog/culture',
    title : 'Category6'
  },
  {
    url : '/blog/culture',
    title : 'Category7'
  },
  {
    url : '/blog/culture',
    title : 'Category8'
  },
]

const dummyTeam = [
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 1',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 2',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 3',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 4',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 5',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 6',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 7',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 8',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 9',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 10',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 11',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
  {
    image: '/assets/traveltour/images/noimagebig.jpg',
    name: 'Employe 12',
    designation : 'Co FOunder',
    detail : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
  },
]

const cmsData = {
    image : '/assets/traveltour/images/blog-and-news.jpg',
    content : `<p></p><p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p><p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p><p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers. <br><br></p><p></p>
        
    <p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p>

    <p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p>

    <p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers.<br>
    &nbsp;</p>`
  }
export {
  searchResult, 
  paxInfo, 
  routeInfos, 
  airportLists, 
  form_data, 
  destinations, 
  clients, 
  dummyPackageData, 
  dummyBlogs, 
  blogCategories,
  dummyTestimonials,
  dummyTeam,
  cmsData
};