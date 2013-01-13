package tunemapschartcrawler;

/**
 * A song
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class Song {
    
    /**
     * The mbid
     */
    private String mbid;
    
    /**
     * The song's name
     */
    private String name;
    
    /**
     * The artist's name
     */
    private String artist;
    
    /**
     * Creates a song
     * @param mbid The mbid
     */
    public Song(String mbid) {
        this(mbid, "", "");
    }
    
    /**
     * Creates a song
     * @param mbid The mbid
     * @param name The name
     * @param artist The artist
     */
    public Song(String mbid, String name, String artist) {
        this.mbid = mbid;
        this.name = name;
        this.artist = artist;
    }

    /**
     * @return the mbid
     */
    public String getMbid() {
        return mbid;
    }

    /**
     * @param mbid the mbid to set
     */
    public void setMbid(String mbid) {
        this.mbid = mbid;
    }

    /**
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @param name the name to set
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     * @return the artist
     */
    public String getArtist() {
        return artist;
    }

    /**
     * @param artist the artist to set
     */
    public void setArtist(String artist) {
        this.artist = artist;
    }
    
    /**
     * Checks whether given object is equal to this object
     * 
     * @param o The object
     * @return True if both are equal, false otherwise
     */
    @Override
    public boolean equals(Object o) {
        if(o instanceof Song) {
            Song s = (Song)o;
            if(s.getMbid().isEmpty() || mbid.isEmpty()) {
                return s.getArtist().equals(artist) && s.getName().equals(name);
            } else {
                return s.getMbid().equals(mbid);
            }
        }
        return false;
    }
    
}
