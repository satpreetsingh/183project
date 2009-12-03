<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<!--
<getSessionNote>
  <getSessionNote User_ID="" Header="" Body="" Original_File_Name="" File_Size="1"></getSessionNote>
-->
<xsl:template match="getStudyGroupNotes">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <table class="sessionNotes">
    <xsl:for-each select="getStudyGroupNote">
    <tr>
      <td class="sessionNote fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            <xsl:value-of select="." />
          </xsl:attribute>        
          <xsl:value-of select="@Header" />
        </a>
      </td>
      <td class="fbFont right">
        <br />
        <xsl:if test="$viewUserId=@User_ID">
          [
          <a target="_top" onclick="return confirm('Really? Delete these notes?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/SessionNotes.php?ns_session=<xsl:value-of select="@SessionId" />&amp;noteId=<xsl:value-of select="@Id" />&amp;funct=DELETENOTE
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
    </tr>
    <tr>
      <td class="sessionNote fbFont">
        <xsl:value-of select="@Body" />
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>
</xsl:stylesheet>
