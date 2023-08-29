import './style.css'
import Header from "../Header";
import Footer from "../Footer";
import Heroes from "../Heroes";
import Features from "../Features";
import Jumbotron from "../Jumbotron";
import Carousel from "../Carousel";
import Pricing from "../Pricing";
import Album from "../Album";
import List from "../List";
import News from "../News";

function Page() {
    return <div>
        <Header title={'My Site'}></Header>
        <Heroes text={'My Text'}></Heroes>
        <Features title={'Feature title'}></Features>
        <Jumbotron></Jumbotron>
        <Carousel></Carousel>
        <Pricing free={'$0'} pro={'$19'} ent={'$29'}></Pricing>
        <Album></Album>
        <List></List>
        <News></News>
        <Footer></Footer>
    </div>
}

export default Page;